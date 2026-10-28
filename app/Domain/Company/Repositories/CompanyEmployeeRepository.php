<?php

namespace App\Domain\Company\Repositories;

use App\Domain\Provider\Models\CompanyEmployee;
use App\Support\Repositories\Main\EloquentMainRepository;

class CompanyEmployeeRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(CompanyEmployee $model)
    {
        parent::__construct($model);
    }

    public function getEmployeesByCompanyId(int $company_id, int $manager_id = null, array $otherAssignedEmployees = []): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->builder()
            ->where('company_id', $company_id)
            ->whereNotIn('id', $otherAssignedEmployees)
            ->get();
    }
}
