<?php

namespace App\Domain\ElmApp\Actions\Order;

use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Illuminate\Database\Eloquent\Model;

class ManuallyAssignDriverAction
{
    private MerchantOrderRepository $merchantOrderRepository;
    private LogOrderHistoryAction $logOrderHistoryAction;

    public function __construct(MerchantOrderRepository $merchantOrderRepository ,LogOrderHistoryAction $logOrderHistoryAction)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
        $this->logOrderHistoryAction = $logOrderHistoryAction;
    }
    public function handle($order_id,array $data ,Model $user)
    { 
        $reason = "Driver Assign Manually By Admin";
        $this->logOrderHistoryAction->handle(
            $order_id,
            MerchantOrderHistoryStatuses::DRIVER_ASSIGNED_MANUALLY->value,
            $reason,
            $user,
        );
        
        return $this->merchantOrderRepository->update($order_id , $data);
    }

}
