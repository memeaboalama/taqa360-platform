<?php

namespace App\Domain\Admin\Repositories;

use App\Domain\AppBase\Models\Nationality\Nationality;
use App\Support\Repositories\Main\EloquentMainRepository;

class NationalityRepository extends EloquentMainRepository
{
    /*
        * replace Model class param with your Repository Model
        */

    public function __construct(Nationality $model)
    {
        parent::__construct($model);
    }
}
