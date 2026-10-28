<?php

namespace App\Domain\MarketPlace\Repositories\Catalog;

use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\Catalog\Category;

class CategoryRepository extends EloquentMainRepository
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }
}
