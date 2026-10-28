<?php

namespace App\Domain\MarketPlace\Repositories\Catalog;

use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\Catalog\ChildCategory;

class ChildCategoryRepository extends EloquentMainRepository
{
    public function __construct(ChildCategory $model)
    {
        parent::__construct($model);
    }
}
