<?php

namespace App\Domain\Provider\Actions\Dashboard\Company;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;
use App\Domain\Provider\Models\Company;
use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateCompanyInfoAction
{
    protected CompanyRepository $companyRepository;

    public function __construct()
    {
        $this->companyRepository = new CompanyRepository(new Company());
    }

    public function handle(int $companyId, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['country_id'] = 1;
        $data['password'] = Hash::make($data['password']);
        $data['phone'] = ltrim($data['phone'], "0");
        return $this->companyRepository->update($companyId, $data);
    }
}
