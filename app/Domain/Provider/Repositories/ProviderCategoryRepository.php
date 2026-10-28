<?php

namespace App\Domain\Provider\Repositories;

use App\Domain\Provider\Models\ProviderCategory;
use App\Support\Repositories\Main\EloquentMainRepository;

class ProviderCategoryRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(ProviderCategory $model)
    {
        parent::__construct($model);
    }
}
