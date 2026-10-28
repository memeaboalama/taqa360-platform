<?php

namespace App\Domain\Company\Actions\Dashboard\Employee;

use App\Domain\Company\Repositories\CompanyEmployeeRepository;
use Illuminate\Support\Facades\Hash;

class UpdateCompanyEmployeeAction
{
    protected CompanyEmployeeRepository $companyEmployeeRepository;


    public function __construct(CompanyEmployeeRepository $companyEmployeeRepository)
    {
        $this->companyEmployeeRepository = $companyEmployeeRepository;
    }

    public function handle(int $element_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {

        if ($data['password'] != "") {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
            unset($data['password_confirmation']);
        }
        $data['phone'] = ltrim($data['phone'], "0");
        $employee = $this->companyEmployeeRepository->update($element_id, $data);
        $employee->roles()->detach();
        $employee->assignRole($data['role']);
        return $employee;
    }
}
