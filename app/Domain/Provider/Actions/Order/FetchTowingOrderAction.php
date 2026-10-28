<?php

namespace App\Domain\Provider\Actions\Order;

use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class FetchTowingOrderAction
{


    private ProviderOrderRepository $providerOrderRepository;

    public function __construct(ProviderOrderRepository $providerOrderRepository)
    {
        $this->providerOrderRepository = $providerOrderRepository;
    }

    /**
     * @param int $order_id
     * @return Model|null
     */
    public function handle(int $order_id): ?Model
    {

        return $this->providerOrderRepository->find(['id','status','provider_id'],['id'=>$order_id]);
    }
}
