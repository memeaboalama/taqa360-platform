<?php

namespace App\Domain\Provider\Actions\Order;

use App\Domain\Provider\Models\ProviderOrder;
use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use App\Support\Enum\OrderActionEnum;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UpdateOrderAction
{


    private ProviderOrderRepository $providerOrderRepository;

    public function __construct(ProviderOrderRepository $providerOrderRepository)
    {
        $this->providerOrderRepository = $providerOrderRepository;
    }

    /**
     * @param ProviderOrder $order
     * @param int $provider_id
     * @param OrderActionEnum $action
     * @return bool|int
     */
    public function handle(ProviderOrder $order,int $provider_id , OrderActionEnum $action): bool|int
    {
        return $this->providerOrderRepository->update_order_action($order,$provider_id,$action);
    }
}
