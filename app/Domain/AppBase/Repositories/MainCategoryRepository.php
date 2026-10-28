<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Category\MainCategory;
use App\Domain\AppBase\Models\Location\City;
use App\Support\Repositories\Main\EloquentMainRepository;

class MainCategoryRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(MainCategory $model)
    {
        parent::__construct($model);
    }
}
