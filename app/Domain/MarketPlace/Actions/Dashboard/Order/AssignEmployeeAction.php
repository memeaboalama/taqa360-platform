<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Domain\MarketPlace\Models\MerchantEmployee;

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
        if (!$order) {
            return null;
        }

        $employee = MerchantEmployee::query()
            ->where('merchant_id', $order->merchant_id)
            ->find($data['employee'] ?? null);
        if (!$employee) {
            return null;
        }

        $order->update(['employee_id' => $data['employee']]);
        return $order;
    }
}
