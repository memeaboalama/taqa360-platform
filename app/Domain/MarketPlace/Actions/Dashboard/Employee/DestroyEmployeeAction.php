<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Employee;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;

class DestroyEmployeeAction
{
    protected MerchantEmployeeRepository $merchantEmployeeRepository;


    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
    }


    public function handle(int $id): bool
    {
        return $this->merchantEmployeeRepository->destroy($id);
    }

}

