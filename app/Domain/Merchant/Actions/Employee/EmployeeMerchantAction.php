<?php

namespace App\Domain\Merchant\Actions\Employee;


use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Domain\MarketPlace\Models\MerchantEmployee;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeMerchantAction
{
    private MerchantEmployeeRepository $merchantEmployeeRepository;

    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
    }

    /**
     * @param array $data
     * @return Model|null
     */
    public function handle(array $data): Model|bool|null
    {
        return $this->merchantEmployeeRepository->update(auth()->id(), $data);
    }

    public function change_password(array $data)
    {
        $data_pass["password"] = Hash::make($data["new_password"]);
        return $this->merchantEmployeeRepository->update(auth()->id(), $data_pass);
    }

    public function find($order, array $data): Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null
    {

        return $this->merchantEmployeeRepository->getEmployeeByOrderId($order, $data);

    }

    public function getMerchantEmployee()
    {

        return $this->merchantEmployeeRepository->getMerchantEmployee();

    }
}
