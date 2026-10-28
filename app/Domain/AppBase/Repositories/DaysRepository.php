<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Car\Brand;
use App\Domain\AppBase\Models\Location\City;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\Day;

class DaysRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Day $model)
    {
        parent::__construct($model);
    }

    public function selectCols(array $cols)
    {
        return $this->modal->select($cols)->get()->toArray();
    }
}
