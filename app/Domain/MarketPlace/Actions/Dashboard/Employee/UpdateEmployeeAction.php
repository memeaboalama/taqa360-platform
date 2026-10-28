<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Employee;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use Illuminate\Support\Facades\Hash;

class UpdateEmployeeAction
{
    protected MerchantEmployeeRepository $merchantEmployeeRepository;


    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
    }


    public function handle(int $element_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {

        if ($data['password'] != "") {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
            unset($data['password_confirmation']);
        }
        $data['phone'] = ltrim($data['phone'], "0");
        $employee = $this->merchantEmployeeRepository->update($element_id, $data);
        $employee->roles()->detach();
        $employee->assignRole($data['role']);
        return $employee;
    }

}

