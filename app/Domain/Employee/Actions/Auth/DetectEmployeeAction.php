<?php

namespace App\Domain\Employee\Actions\Auth;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetectEmployeeAction
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
        if (!Auth::guard('merchant_employee')->attempt(request()->only('email','password'))) {
            return false;
        }
        return $this->merchantEmployeeRepository->findByCols(cols: ['email' => $data["email"]] ,with:["branch", "branchManager"]);
    }

    public function getByPhone(array $data): Model|bool|null
    {
        return $this->merchantEmployeeRepository->findByCols(cols: ['phone' => $data['phone']] ,with:["branch", "branchManager"]);
    }
}
