<?php

namespace App\Domain\Company\Actions\Dashboard\Employee;

use App\Domain\Company\Repositories\CompanyEmployeeRepository;

class FetchSingleCompanyEmployeeAction
{
    protected CompanyEmployeeRepository $companyEmployeeRepository;


    public function __construct(CompanyEmployeeRepository $companyEmployeeRepository)
    {
        $this->companyEmployeeRepository = $companyEmployeeRepository;
    }

    public function handle($id, $company_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->companyEmployeeRepository->findByCols(['company_id' => $company_id, 'id' => $id]);
    }

}
