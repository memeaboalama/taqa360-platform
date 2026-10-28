<?php

namespace App\Domain\MarketPlace\Actions\Order;


use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Illuminate\Support\Facades\Session;


class OrderAction
{

    private MerchantOrderRepository $merchantOrderRepository;


    public function __construct(MerchantOrderRepository $merchantOrderRepository)
    {

        $this->merchantOrderRepository = $merchantOrderRepository;


    }

    public function handle(int $token): \Illuminate\Database\Eloquent\Model|null
    {
        $cols = ['id', 'user_id', 'channel', 'total_after_vat'];
        $order = $this->merchantOrderRepository->find(cols: $cols, conditions: ['id' => $token]);

        if ($order) {
            Session::put('payment_merchant_iframe', [
                'order_id' => $order?->id,
            ]);
            $order->PriceAtSmallestUnit = $order->total_after_vat * 100;
            $order->key = $order->id;
            return $order;
        }
        return null;

    }

    public function update($id, $status): ?\Illuminate\Database\Eloquent\Model
    {


        return $this->merchantOrderRepository->update($id, ["status" => $status]);


    }
    public function updateAdminNote($id, $note): ?\Illuminate\Database\Eloquent\Model
    {

        return $this->merchantOrderRepository->update($id, ["admin_note" => $note]);

    }
    public function updateMark($id, $mark): ?\Illuminate\Database\Eloquent\Model
    {

        return $this->merchantOrderRepository->update($id, ["mark" => $mark]);

    }
    public function getById($id): ?\Illuminate\Database\Eloquent\Model
    {


        return $this->merchantOrderRepository->findByCols(['id' => $id]);


    }

}
