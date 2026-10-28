<?php

namespace App\Domain\Provider\Jobs;

use App\Domain\Provider\Events\NewTowingOrderEvent;
use App\Domain\Provider\Models\ProviderOrder;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use App\Support\Enum\OrderActionEnum;
use Carbon\Carbon;
use Domain\Provider\Repositories\ProviderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;

class FollowTowingOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $cache_key_name;
    private ProviderOrder $order;


    /**
     * Create a new job instance.
     */
    public function __construct(string $cache_key_name,ProviderOrder $order)
    {
        $this->cache_key_name = $cache_key_name;
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    #[NoReturn] public function handle(ProviderOrderRepository $providerOrderRepository,ProviderRepository $providerRepository): void
    {
        $order_has_provider = $providerOrderRepository->find(['id','provider_id','status'],['id'=>$this->order?->id , 'status' => OrderActionEnum::PENDING]);

        if($order_has_provider?->provider_id && $order_has_provider?->status != OrderActionEnum::PENDING ) return;

        $near_providers = Cache::tags('near_providers')->get($this->cache_key_name);

        Log::channel('single')->info('near_providers '.($near_providers ? count($near_providers) : 0));

        foreach($near_providers as $provider)
        {
            // check first provider's id is available inside caching tag if true skip
            $provider_on_ride = Cache::tags('on_ride_providers')->get('on_ride_providers.'.$provider['id']);
            Log::channel('single')->info('provider_on_ride '.$provider['id']);

            if($provider_on_ride) continue ;
            Log::channel('single')->info('passed continue '.$provider['id']);

            // move first provider's id to not available caching tag for 1 minutes least
            Cache::tags('on_ride_providers')->remember('on_ride_providers.'.$provider['id'],160,fn() => $provider);

            Cache::tags('on_order_queue')->forget('on_order.'.$this->order?->id);

            Cache::tags('on_order_queue')->remember('on_order.'.$this->order?->id,160,fn() => $provider);

            NewTowingOrderEvent::dispatchIf(isset($provider['id']),$provider,$this->order);
            Log::channel('single')->info('here 1');

            // if not accept rerun this job again and remove first provider
            dispatch(new SearchForTowingProvidersJob($this->order))->delay(Carbon::now()->addSeconds(45));
            Log::channel('single')->info('here 2');

            // mark provider as has requested to follow order
            $providerRepository->insert_provider_order_action($provider['id'],$order_has_provider,OrderActionEnum::PENDING);
            Log::channel('single')->info('provider inserted');

            return;
        }
        Log::channel('single')->info('here 3');
        dispatch(new SearchForTowingProvidersJob($this->order))->delay(Carbon::now()->addSeconds(30));

    }
}
