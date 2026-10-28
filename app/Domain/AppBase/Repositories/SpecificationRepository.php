<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Specification;
use App\Support\Repositories\Main\EloquentMainRepository;

class SpecificationRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Specification $model)
    {
        parent::__construct($model);
    }
}
