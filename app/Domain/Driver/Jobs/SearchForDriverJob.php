<?php

namespace App\Domain\Driver\Jobs;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Support\Enum\StatusEnum;
use Carbon\Carbon;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\TempOrder;
use Domain\Provider\Repositories\ProviderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Log;
use JetBrains\PhpStorm\NoReturn;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Illuminate\Support\Facades\Log;

class SearchForDriverJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private MerchantOrder $order;

    /**
     * Create a new job instance.
     */
    public function __construct(MerchantOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
   public function handle()
    {

        $order = MerchantOrder::query()->find($this->order?->id);
        if ($order?->driver_id || $order?->status != MerchantOrderStatuses::UPCOMING) return;

        /// user location as point .....
        if (!$order?->branch?->latitude || !$order?->branch?->longitude) return;
        $location = new Point($order?->branch?->latitude, $order?->branch?->longitude, Srid::WGS84->value);

        $maximumDistance = (GeneralSetting::first()?->driver_search_capacity);
        $kilos = [0, (int) round($maximumDistance /2,0) , (int) $maximumDistance];

        foreach ($kilos as $index=> $kilo) {

            $nearest_driver = Driver::where('network_status', true)
                ->where('status', '=', StatusEnum::ACTIVE)
                ->withDistance('location', $location)
                ->orderByDistance('location', $location)
                ->whereDistance('location', $location, '<=', $kilo * 1000)
                ->limit(10)
                ->get();


            if ($nearest_driver->count() >= 10 && !next( $kilos )) break;
            if ($kilo >= $maximumDistance && $nearest_driver->count() == 0 && !next( $kilos )) break;
            if ($kilo >= $maximumDistance && !next( $kilos ) && ($nearest_driver->count() < 10 && $nearest_driver->count() > 0)) break;

        }


        // Log::channel("daily")->info("driver : SearchForDriverJob " . json_encode($order));
        if ($nearest_driver->count() <= 0) {
            dispatch(new SearchForDriverJob($this->order))->delay(Carbon::now()->addSeconds(15));
            return;
        }


        foreach ($nearest_driver as $item) {
            TempOrder::query()->create([
                'order_id' => $order->id,
                'driver_id' => $item->id,
                'status' => $order->status,
                'created_at' => Carbon::now(),
            ]);
            Log::channel("console")->info("SearchForDriverJob driver : ".$item->id);
        }
        foreach ($nearest_driver as $driver) {
        try {
            // dispatch(new \App\Domain\Notification\Jobs\Driver\SendNotifyOrderV2($order,$driver))->afterResponse();
            // dispatch(new \App\Domain\Notification\Jobs\Driver\SendNotifyOrderV2($driver->id))->afterResponse();
            dispatch(new \App\Domain\Notification\Jobs\Driver\SendNotifyOrderV2($driver->id));
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            Log::error($exception);
        }
    }
        // foreach ($nearest_driver as $driver) {
        //     NewOrderEvent::dispatch($driver, $order);
        //     dispatch_sync(new SendNotifyOrder($order, $driver));
        // }
    }
}
