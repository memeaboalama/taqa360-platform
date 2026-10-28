<?php

namespace App\Domain\Employee\Actions\Auth;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordAction
{
    /**
     * @param array $data
     * @return Model|null
     */
    public function handle(array $data)
    {
        $employee = auth()->guard('employee-api')->user();
        $data['password'] = Hash::make($data['password']);
        $employee->update([
            'password' => $data['password']
        ]);
        return $employee;
    }
}
