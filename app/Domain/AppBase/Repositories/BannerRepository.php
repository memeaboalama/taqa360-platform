<?php

namespace App\Domain\AppBase\Repositories;

use App\Domain\AppBase\Models\Banner;
use App\Support\Repositories\Main\EloquentMainRepository;

class BannerRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Banner $model)
    {
        parent::__construct($model);
    }
}
