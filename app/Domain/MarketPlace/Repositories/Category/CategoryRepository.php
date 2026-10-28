<?php

namespace App\Domain\MarketPlace\Repositories\Category;

use App\Domain\AppBase\Models\City;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\MarketPlaceCategory;

class CategoryRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(MarketPlaceCategory $model)
    {
        parent::__construct($model);
    }
}
