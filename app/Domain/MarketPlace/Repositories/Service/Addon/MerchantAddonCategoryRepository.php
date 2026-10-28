<?php

namespace App\Domain\MarketPlace\Repositories\Service\Addon;

use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\MerchantAddonCategory;

class MerchantAddonCategoryRepository  extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(MerchantAddonCategory $model)
    {
        parent::__construct($model);
    }
}

