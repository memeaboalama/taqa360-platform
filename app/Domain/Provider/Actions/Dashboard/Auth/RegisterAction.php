<?php

namespace App\Domain\Provider\Actions\Dashboard\Auth;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterAction
{
    protected CompanyRepository $companyRepository;


    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['password'] = Hash::make($data['password']);
        $data['country_id'] = 1;
        $company = $this->companyRepository->store($data);
        Auth::guard('company')->login($company);
        return $company;
    }

}
