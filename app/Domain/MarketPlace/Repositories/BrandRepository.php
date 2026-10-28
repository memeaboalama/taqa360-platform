<?php

namespace App\Domain\MarketPlace\Repositories;

use App\Support\Repositories\Main\EloquentMainRepository;
use App\Domain\MarketPlace\Models\Brand;

class BrandRepository extends EloquentMainRepository
{
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }
}
