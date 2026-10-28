<?php

namespace App\Domain\Provider\Repositories\Company;

use App\Domain\Provider\Models\Company;
use App\Support\Repositories\Main\EloquentMainRepository;

class CompanyRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    public function getAllCompaniesAsBuilder(array $cols, array $with ,array $conditions = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder($cols, $with, $conditions, 'desc');
    }
}

