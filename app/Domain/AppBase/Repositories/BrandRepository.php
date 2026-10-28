<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Location\City;
use App\Support\Repositories\Main\EloquentMainRepository;

class BrandRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }
}
