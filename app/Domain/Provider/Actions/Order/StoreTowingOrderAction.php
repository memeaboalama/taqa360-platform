<?php

namespace App\Domain\Provider\Actions\Order;

use App\Domain\Provider\Repositories\ProviderOrderRepository;
use App\Support\Enum\VehicleType;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StoreTowingOrderAction
{


    private ProviderOrderRepository $providerOrderRepository;

    public function __construct(ProviderOrderRepository $providerOrderRepository)
    {
        $this->providerOrderRepository = $providerOrderRepository;
    }

    /**
     * @param array $data
     * @param array $towingCalculation
     * @return Model|null
     */
    public function handle(array $data , array $towingCalculation): ?Model
    {
        $payload = [
          'user_id'                     =>  auth()->id(),
          'provider_sub_services_id'    =>  $data['sub_service_id'],
          'provider_vehicle_type'       =>  VehicleType::TOWING,
          'total_order'                 =>  $towingCalculation['total_price'] - $towingCalculation['vat'],
          'vat'                         =>  $towingCalculation['vat'],
          'total_after_vat'             =>  $towingCalculation['total_price'],
          'client_name'                 =>  auth()->user()?->name ?? '',
          'client_full_phone'           =>  auth()->user()?->phone_number,
          'client_location'             =>  new Point($data['start_location']['latitude'],$data['start_location']['longitude'],Srid::WGS84->value),
        ];

        return $this->providerOrderRepository->find(['*'],['id'=>$this->providerOrderRepository->store($payload)->id]);
    }
}
