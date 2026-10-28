<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;

class AssignEmployeeAction
{
    protected MerchantOrderRepository $orderRepository;


    public function __construct(MerchantOrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }


    public function handle(int $id, $data): \Illuminate\Database\Eloquent\Model|null
    {
        $order = $this->orderRepository->findByCols(['id'=>$id]);
        $order->update(['employee_id' => $data['employee']]);
        return $order;
    }
}
