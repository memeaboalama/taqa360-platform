<?php

namespace App\Domain\MarketPlace\Repositories\Branch\WorkingDays;

use App\Domain\AppBase\Models\City;
use App\Domain\MarketPlace\Filters\MerchantFilter;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\MerchantBranch;
use Domain\MarketPlace\Models\MerchantBranchWorkday;

class WorkingDaysRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(MerchantBranchWorkday $model)
    {
        parent::__construct($model);
    }

    public function getMerchantBranchWorkingDays(mixed $merchant_id): \Illuminate\Database\Eloquent\Collection|array
    {
        return $this->builder()->where('merchant_branch_id', $merchant_id)->get();
    }

    public function getMerchantBranchByDay(array $filters)
    {
        return $this->builder()->where($filters)->first();
    }

}
