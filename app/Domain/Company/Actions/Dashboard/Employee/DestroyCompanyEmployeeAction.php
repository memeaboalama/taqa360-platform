<?php

namespace App\Domain\Company\Actions\Dashboard\Employee;

use App\Domain\Company\Repositories\CompanyEmployeeRepository;

class DestroyCompanyEmployeeAction
{
    protected CompanyEmployeeRepository $companyEmployeeRepository;


    public function __construct(CompanyEmployeeRepository $companyEmployeeRepository)
    {
        $this->companyEmployeeRepository = $companyEmployeeRepository;
    }

    public function handle(int $id): bool
    {
        return $this->companyEmployeeRepository->destroy($id);
    }
}
