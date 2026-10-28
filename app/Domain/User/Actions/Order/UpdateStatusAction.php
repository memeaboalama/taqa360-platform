<?php

namespace App\Domain\User\Actions\Order;

use App\Domain\MarketPlace\Repositories\Order\MerchantOrderRepository;
use Illuminate\Database\Eloquent\Model;

class UpdateStatusAction
{
    private MerchantOrderRepository $merchantOrderRepository;

    public function __construct(MerchantOrderRepository $merchantOrderRepository)
    {
        $this->merchantOrderRepository = $merchantOrderRepository;
    }

    public function update(array $data): ?Model
    {
        return $this->merchantOrderRepository->update($data["order"], ["status" => $data["status"]]);
    }
}
