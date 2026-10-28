<?php

namespace App\Domain\MarketPlace\Actions\Api\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;

class RateOrderAction
{
    private MerchantOrderRepository $merchantOrderRepository;
    public function __construct(MerchantOrderRepository $merchantOrderRepository)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
    }

    public function handle(array $data, int $ordrrId)
    {
        return $this->merchantOrderRepository->update($ordrrId, $data);
    }
}
