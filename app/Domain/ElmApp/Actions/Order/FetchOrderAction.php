<?php

namespace App\Domain\ElmApp\Actions\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Database\Eloquent\Model;

class FetchOrderAction
{
    private MerchantOrderRepository $merchantOrderRepository;

    public function __construct(MerchantOrderRepository $merchantOrderRepository)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
    }
    public function handle(array $data)
    {
        $paginate = $data["per_page"] ?? 10;
        return $this->merchantOrderRepository->getOrdersByElmStatus($data,['orders_history'],$paginate);
    }

}
