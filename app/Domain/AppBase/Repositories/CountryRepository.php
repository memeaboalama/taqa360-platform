<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Location\Country;
use App\Support\Repositories\Main\EloquentMainRepository;

class CountryRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Country $model)
    {
        parent::__construct($model);
    }


}
