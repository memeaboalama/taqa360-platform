<?php

namespace App\Domain\Provider\Actions\Order;

use App\Domain\Provider\Models\Provider;
use App\Domain\Provider\Models\ProviderOrder;
use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use App\Support\Enum\OrderActionEnum;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UpdateOrderStatusAction
{


    private ProviderOrderRepository $providerOrderRepository;

    public function __construct(ProviderOrderRepository $providerOrderRepository)
    {
        $this->providerOrderRepository = $providerOrderRepository;
    }

    /**
     * @param ProviderOrder $order
     * @param Provider $provider
     * @param OrderActionEnum $action
     * @return Model|null
     */
    public function handle(ProviderOrder $order,Provider $provider , OrderActionEnum $action): ?Model
    {
        $payload = [
            'provider_id'   =>  $provider->id,
            'company_id'        =>  $provider->comapny_id,
            'status'        =>  $action,

        ];

        return $this->providerOrderRepository->update($order->id,$payload);
    }
}
