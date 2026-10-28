<?php

namespace App\Domain\MarketPlace\Repositories\Service;



use App\Domain\MarketPlace\Filters\MerchantFilter;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MerchantAddonCategory;
use Domain\MarketPlace\Models\MerchantService;

use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class MerchantCategoryAddonRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(MerchantAddonCategory $model)
    {
        parent::__construct($model);
    }
    public function all_active(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc'): Collection
    {
        return $this->modal::with($relations)->active()->where($condition)->select($cols)->orderBy('id' , $order)->get();
    }




}
