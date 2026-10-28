<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Employee;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use Illuminate\Support\Facades\Hash;

class UpdateEmployeePasswordAction
{
    protected MerchantEmployeeRepository $merchantEmployeeRepository;


    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
    }


    public function handle(int $element_id, string $password): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->merchantEmployeeRepository->update($element_id, [
            'password' => Hash::make($password),
        ]);
    }

}
