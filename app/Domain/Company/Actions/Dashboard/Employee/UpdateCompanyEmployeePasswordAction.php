<?php

namespace App\Domain\Company\Actions\Dashboard\Employee;

use App\Domain\Company\Repositories\CompanyEmployeeRepository;
use Illuminate\Support\Facades\Hash;

class UpdateCompanyEmployeePasswordAction
{
    protected CompanyEmployeeRepository $companyEmployeeRepository;

    public function __construct(CompanyEmployeeRepository $companyEmployeeRepository)
    {
        $this->companyEmployeeRepository = $companyEmployeeRepository;
    }

    public function handle(int $element_id, string $password): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->companyEmployeeRepository->update($element_id, [
            'password' => Hash::make($password),
        ]);
    }
}