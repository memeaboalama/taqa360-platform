<?php

namespace App\Domain\MarketPlace\Repositories\TypeService;

use \App\Domain\AppBase\Models\CityTypeServicePackages;
use App\Support\Repositories\Main\EloquentMainRepository;

class TypeServiceRepository extends EloquentMainRepository
{
    public function __construct(CityTypeServicePackages $model)
    {
        parent::__construct($model);
    }
}
