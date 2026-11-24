<?php

namespace App\Domain\ElmApp\Actions\Order;

use App\Application\Marketplace\Enums\MerchantOrderHistoryStatuses;
use App\Domain\MarketPlace\Actions\Api\Order\LogOrderHistoryAction;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use App\Domain\Notification\Notifications\Driver\OrderAssigned;
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
    public function handle($order_id, array $data, Model $user, string $reason = 'Driver Assign Manually By Admin')
    { 
        $order = $this->merchantOrderRepository->findByCols(['id' => $order_id]);
        if (!$order) {
            return null;
        }

        $updatedOrder = $this->merchantOrderRepository->update($order_id, $data);
        if (!$updatedOrder) {
            return null;
        }

        $this->logOrderHistoryAction->handle(
            $order_id,
            MerchantOrderHistoryStatuses::DRIVER_ASSIGNED_MANUALLY->value,
            $reason,
            $user,
        );

        $updatedOrder->loadMissing('driver');
        $driver = $updatedOrder->driver;
        if ($driver) {
            $driver->notify(new OrderAssigned($updatedOrder));
        }

        return $updatedOrder;
    }

}
