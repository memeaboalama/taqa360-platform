<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;

class CancelOrderAction
{
    protected MerchantOrderRepository $orderRepository;


    public function __construct(MerchantOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        $order = $this->orderRepository->findByCols(['id'=>$id]);
        if ($order) {
            $order->update(['status' => \App\Application\Marketplace\Enums\MerchantOrderStatuses::CANCELLED->value]);
        }
        return $order;
    }
}
