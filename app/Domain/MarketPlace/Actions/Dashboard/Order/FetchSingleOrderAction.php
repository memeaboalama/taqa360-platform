<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;

class FetchSingleOrderAction
{
    protected MerchantOrderRepository $orderRepository;


    public function __construct(MerchantOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    public function handle($id, int $merchant_id = null): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->orderRepository->findByCols(['merchant_id' => $merchant_id, 'id' => $id]);
    }

    public function getById($id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->orderRepository->findByCols(['id' => $id]);
    }

    public function getByUser(int $id, int $user_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->orderRepository->findByCols(['user_id' => $user_id, 'id' => $id]);
    }

}

