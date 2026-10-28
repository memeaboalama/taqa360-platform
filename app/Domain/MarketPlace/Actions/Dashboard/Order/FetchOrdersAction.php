<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;

class FetchOrdersAction
{
    protected MerchantOrderRepository $orderRepository;


    public function __construct(MerchantOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    public function handle(int $merchant_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->orderRepository->findByCols(['merchant_id' => $merchant_id]);
    }
}
