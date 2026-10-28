<?php

namespace App\Domain\MarketPlace\Repositories\Merchant;

use App\Domain\MarketPlace\Models\Package;
use App\Support\Repositories\Main\EloquentMainRepository;

class PackageRepository extends EloquentMainRepository
{
    public function __construct(Package $model)
    {
        parent::__construct($model);
    }
}
