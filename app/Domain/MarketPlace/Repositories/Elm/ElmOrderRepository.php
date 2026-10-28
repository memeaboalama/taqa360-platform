<?php

namespace App\Domain\MarketPlace\Repositories\Elm;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrder;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\ElmOrder;
use Domain\MarketPlace\Models\MarketPlaceCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ElmOrderRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public array $months;

    public function __construct(ElmOrder $model)
    {
        parent::__construct($model);
    }
}
