<?php

namespace App\Domain\Driver\Jobs;


use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;

use App\Domain\Notification\Jobs\Provider\SendNotifyOrder;
use App\Domain\Provider\Events\NewCarServiceOrderEvent;
use App\Domain\Provider\Events\NewTowingOrderEvent;
use App\Domain\Provider\Jobs\SearchForProvidersJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use JetBrains\PhpStorm\NoReturn;


class FollowOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private string $cache_key_name;
    private MerchantOrder $order;

    /**
     * Create a new job instance.
     */
    public function __construct(string $cache_key_name, MerchantOrder $order)
    {
        $this->cache_key_name = $cache_key_name;
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    #[NoReturn] public function handle()
    {
        $order_has_provider=MerchantOrder::query()->find($this->order?->id);

        if ($order_has_provider?->driver_id ||$order_has_provider?->status != MerchantOrderStatuses::PENDING) return;

        $near_drivers = Cache::tags('near_drivers')->get($this->cache_key_name);


        foreach ($near_drivers as $driver){
            // check first provider's id is available inside caching tag if true skip
            $provider_on_ride = $this->checkIfProviderOnRide($driver['id']);

            // if provider has another ride move to next one
            if($provider_on_ride) continue ;


            NewTowingOrderEvent::dispatchIf(isset($driver['id']),$driver,$this->order);

            // send fcm notify for selected provider
          //  dispatch_sync(new SendNotifyOrder($this->order,$driver));


            // increase provider attempts for no response
            $provider_attempts = $this->setProvider_attempts($driver);


            if($provider_attempts >= 3)
            {
//                $updateProfileProviderAction->handle(['network_status'  => 'offline'],$provider['id']);
//
//                dispatch_sync(new ProviderNetworkStatusChanged($provider));
//
//                return;
            }




            return;
        }




//        dispatch(new SearchForDriverJob($this->order))->delay(Carbon::now()->addSeconds(15));
    }

    public function checkIfProviderOnRide($id): mixed
    {
        $provider_on_ride = Cache::tags('on_ride_drivers')->get('on_ride_drivers.' . $id);
        return $provider_on_ride;
    }

    public function markProviderAsNotAvailable($driver,$end_time): void
    {
        Cache::tags('on_ride_drivers')->remember('on_ride_providers.' . $driver['id'], 80, fn() => ['driver' =>  $driver, 'order' => $this->order,'end-time'   => $end_time ]);

        Cache::tags('on_order_queue')->forget('on_order.' . $this->order?->id);

        Cache::tags('on_order_queue')->rememberForever('on_order.' . $this->order?->id, fn() =>  $driver);
    }

    public function setProvider_attempts($driver): mixed
    {
        $provider_attempts = Cache::tags('driver_attempts')->get('driver_attempts.' . $driver['id']);

        $provider_attempts = isset($provider_attempts['attempts']) ? $provider_attempts['attempts'] + 1 : 1;

        Cache::tags('driver_attempts')->forget('provider_attempts.' . $driver['id']);

        Cache::tags('driver_attempts')->remember('provider_attempts.' . $driver['id'], 60 * 60 * 3, fn() => ['provider' => $driver, 'order' => $this->order, 'attempts' => $provider_attempts]);

        return $provider_attempts;
    }


}
