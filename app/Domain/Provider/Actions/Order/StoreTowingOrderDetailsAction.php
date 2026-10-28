<?php

namespace App\Domain\Provider\Actions\Order;

use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StoreTowingOrderDetailsAction
{


    private ProviderOrderRepository $providerOrderRepository;

    public function __construct(ProviderOrderRepository $providerOrderRepository)
    {
        $this->providerOrderRepository = $providerOrderRepository;
    }

    /**
     * @param Model $providerOrder
     * @param array $data
     * @param array $towingCalculation
     * @param ProviderSubService $towing_service
     * @return Model|null
     */
    public function handle(Model $providerOrder , array $data , array $towingCalculation ,ProviderSubService $towing_service): ?Model
    {
        $payload = [
            'user_id'                   =>  $providerOrder->user_id,
            'pickup_address'            =>  new Point($data['start_location']['latitude'],$data['start_location']['longitude'],Srid::WGS84->value),
            'dropoff_address'           =>  new Point($data['destination_location']['latitude'],$data['destination_location']['longitude'],Srid::WGS84->value),
            'pickup_address_title'      => $towingCalculation['start_address'],
            'dropoff_address_title'     => $towingCalculation['end_address'],
            'price_per_km'              => $towing_service->towing_price_per_km,
            'price_per_minute'          => $towing_service->price_per_minute,
            'total_distance_in_km'      => $towingCalculation['distance_in_km_value'],
            'total_time_in_minutes'     => $towingCalculation['duration_in_min_value'],
            'total_price'               => $towingCalculation['total_price'],
        ];

        return $this->providerOrderRepository->storeOrderDetails($payload,$providerOrder);
    }
}
