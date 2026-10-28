<?php

namespace App\Domain\Provider\Actions\Order;

use App\Domain\Provider\Models\ProviderOrder;
use App\Domain\Provider\Models\ProviderSubService;
use App\Domain\Provider\Repositories\ProviderOrderRepository;
use App\Domain\User\Models\User;
use App\Support\Enum\OrderActionEnum;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UpdateFullOrderAction
{


    private ProviderOrderRepository $providerOrderRepository;

    public function __construct(ProviderOrderRepository $providerOrderRepository)
    {
        $this->providerOrderRepository = $providerOrderRepository;
    }

    public function handle(int $order_id , User|Authenticatable $user, OrderActionEnum $action)
    {
        $payload = [
            'status'    =>  $action
        ];

        return $this->providerOrderRepository->update_order_information_with_user(['id'=>$order_id,'user_id'=>$user->id,'status' => OrderActionEnum::PENDING->value],$payload);
    }
}
