<?php

namespace App\Domain\Provider\Jobs;

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

class SearchForTowingProvidersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ProviderOrder $order;

    /**
     * Create a new job instance.
     */
    public function __construct(ProviderOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    #[NoReturn] public function handle(ProviderRepository $providerRepository,ProviderOrderRepository $providerOrderRepository): void
    {
        $order = $providerOrderRepository->find(['id','provider_id','status'],['id'=>$this->order?->id]);

        if($order?->provider_id || $order?->status != OrderActionEnum::PENDING ) return;

        $near_providers = $providerRepository->get_nearest_to($this->order->client_location,5 * 1000,$order);

        Log::channel('single')->info($near_providers);

        $cache_name = "near_providers_order_".$this->order?->id;

        Cache::tags('near_providers')->forget($cache_name);

        Cache::tags('near_providers')->remember($cache_name,60 * 5, fn() => $near_providers->toArray());

        if($near_providers->count() <= 0){
            dispatch(new SearchForTowingProvidersJob($this->order))->delay(Carbon::now()->addSeconds(30));
            return;
        }

        dispatch(new FollowTowingOrderJob($cache_name,$this->order));
    }
}
