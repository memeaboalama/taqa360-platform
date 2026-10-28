<?php

namespace App\Domain\Admin\Actions\Dashboard\Company;

use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Illuminate\Support\Facades\Hash;

class StoreCompanyAction
{
    protected CompanyRepository $companyRepository;


    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['country_id'] = 1;
        $data['password'] = Hash::make($data['password']);
        $data['phone'] = ltrim($data['phone'], "0");
        return $this->companyRepository->store($data);
    }
}
