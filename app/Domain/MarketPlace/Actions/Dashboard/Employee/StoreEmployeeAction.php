<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Employee;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use Illuminate\Support\Facades\Hash;

class StoreEmployeeAction
{
    protected MerchantEmployeeRepository $merchantEmployeeRepository;


    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['country_id'] = 1;
        $data['password'] = Hash::make($data['password']);
        $data['phone'] = ltrim($data['phone'], "0");
        $employee = $this->merchantEmployeeRepository->store($data);
        $employee->assignRole($data['role']);
        return $employee;
    }

}

