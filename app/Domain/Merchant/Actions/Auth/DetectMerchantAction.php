<?php

namespace App\Domain\Merchant\Actions\Auth;


use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DetectMerchantAction
{
    private MerchantEmployeeRepository $merchantEmployeeRepository;
    private MerchantRepository $merchantRepository;

    public function __construct(MerchantEmployeeRepository $merchantEmployeeRepository,MerchantRepository $merchantRepository)
    {
        $this->merchantEmployeeRepository = $merchantEmployeeRepository;
        $this->merchantRepository = $merchantRepository;
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

        return $this->merchantEmployeeRepository->findByCols(cols: ['email' => $data["email"]],with:["branch"]);
    }

    public function detectMerchantByPhone(array $data): Model|bool|null
    {
        return $this->merchantRepository->findByCols(cols: ['phone' => $data["phone"]],with:[]);
    }
}
