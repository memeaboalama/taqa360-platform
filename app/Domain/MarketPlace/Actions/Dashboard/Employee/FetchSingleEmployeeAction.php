<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Employee;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;

class FetchSingleEmployeeAction
{
    protected MerchantEmployeeRepository $merchantEmployeeRepository;


    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
    }


    public function handle($id, $merchant_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->merchantEmployeeRepository->findByCols(['merchant_id' => $merchant_id, 'id' => $id]);
    }

}

