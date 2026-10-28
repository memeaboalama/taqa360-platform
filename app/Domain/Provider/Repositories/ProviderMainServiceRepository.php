<?php

namespace App\Domain\Provider\Repositories;

use App\Domain\Provider\Models\ProviderMainService;
use App\Domain\Provider\Models\ProviderSubService;
use App\Support\Repositories\Main\EloquentMainRepository;

class ProviderMainServiceRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(ProviderMainService $model)
    {
        parent::__construct($model);
    }
}
