<?php

namespace App\Domain\ElmApp\Actions\Company;

use App\Domain\Provider\Repositories\Company\CompanyRepository;
use Illuminate\Database\Eloquent\Model;

class FetchCompanyAction
{
    private CompanyRepository $companyRepository;

    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }
    public function handle(array $data)
    {
        return $this->companyRepository
        ->getAllCompaniesAsBuilder(['*'],[],['id' => $data['company_id']])
        ->get();
    }
}
