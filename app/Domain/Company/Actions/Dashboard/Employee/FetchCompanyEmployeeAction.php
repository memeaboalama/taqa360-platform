<?php

namespace App\Domain\Company\Actions\Dashboard\Employee;

use App\Domain\Company\Repositories\CompanyEmployeeRepository;
use App\Support\Enum\StatusEnum;

class FetchCompanyEmployeeAction
{
    protected CompanyEmployeeRepository $companyEmployeeRepository;


    public function __construct(CompanyEmployeeRepository $companyEmployeeRepository)
    {
        $this->companyEmployeeRepository = $companyEmployeeRepository;
    }


    public function handle(): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->companyEmployeeRepository
            ->all(cols: ['id', 'name'], condition: ['status' => StatusEnum::ACTIVE, 'company_id' => resolve('_InitialCompany')]);
    }

    public function getByCompany(int $company_id = null): array|\Illuminate\Database\Eloquent\Collection
    {
        return $this->companyEmployeeRepository->all(cols: ['id', 'name'], condition: ['status' => StatusEnum::ACTIVE, 'merchant_id' => $company_id]);
    }


}
