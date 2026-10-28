<?php

namespace Domain\AppBase\Repositories;

use Domain\AppBase\Models\AdCategoryContent;
use App\Support\Repositories\Main\EloquentMainRepository;
class AdCategoryContentRepository extends EloquentMainRepository
{
    public function __construct(AdCategoryContent $model)
    {
        parent::__construct($model);
    }
}
