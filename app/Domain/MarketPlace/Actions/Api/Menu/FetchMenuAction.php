<?php

namespace App\Domain\MarketPlace\Actions\Api\Menu;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use App\Support\Enum\StatusEnum;

class FetchMenuAction
{
    private MerchantServiceRepository $merchantServiceRepository;


    public function __construct(MerchantServiceRepository $merchantServiceRepository)
    {
        $this->merchantServiceRepository = $merchantServiceRepository;
    }

    public function handle()
    {
        return $this->merchantServiceRepository->all(cols: ['id', 'title'], condition: ['status' => StatusEnum::ACTIVE->value], order: 'DESC');
    }

    public function getByBranchEmp($employee)
    {
        $branch = $employee->branch()->first();
        if (!$branch)
            $branch = $employee->branchManager()->first();

        $menu = $branch->menu()
            ->with('categories')
            ->select(['id', 'title'])
            ->where(['status' => StatusEnum::ACTIVE->value])
            // ->where(['updated' => "accepted"])
            ->first();
        return $menu;
    }

    public function getByMerchantId($merchantId)
    {
        return $this->merchantServiceRepository->all(cols: ['id', 'title'], condition: ['merchant_id' => $merchantId,'status' => StatusEnum::ACTIVE->value], order: 'DESC');
    }
}
