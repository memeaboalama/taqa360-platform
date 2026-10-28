<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Employee;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use App\Support\Enum\StatusEnum;

class FetchEmployessAction
{
    protected MerchantEmployeeRepository $merchantEmployeeRepository;


    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
    }


    public function handle(): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->merchantEmployeeRepository
            ->all(cols: ['id', 'name'], condition: ['status' => StatusEnum::ACTIVE, 'merchant_id' => resolve('_InitialMerchant')]);
    }

    public function getByMerchant(int $merchant_id = null): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->merchantEmployeeRepository->all(cols: ['id', 'name'], condition: ['status' => StatusEnum::ACTIVE, 'merchant_id' => $merchant_id]);
    }

    public function getTop(int $count): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->merchantEmployeeRepository
            ->all(cols: ['id', 'name', 'phone', 'email']
                , condition: ['status' => StatusEnum::ACTIVE
                    , 'merchant_id' => resolve('_InitialMerchant')])->take($count);
    }
}
