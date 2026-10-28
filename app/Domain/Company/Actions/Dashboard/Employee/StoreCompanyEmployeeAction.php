<?php

namespace App\Domain\Company\Actions\Dashboard\Employee;

use App\Domain\Company\Repositories\CompanyEmployeeRepository;
use Illuminate\Support\Facades\Hash;

class StoreCompanyEmployeeAction
{
    protected CompanyEmployeeRepository $companyEmployeeRepository;


    public function __construct(CompanyEmployeeRepository $companyEmployeeRepository)
    {
        $this->companyEmployeeRepository = $companyEmployeeRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['country_id'] = 1;
        $data['password'] = Hash::make($data['password']);
        $data['phone'] = ltrim($data['phone'], "0");
        $employee = $this->companyEmployeeRepository->store($data);
        $employee->assignRole($data['role']);
        return $employee;
    }
}
