<?php

namespace App\Domain\MarketPlace\Repositories\Service;



use App\Domain\MarketPlace\Filters\MerchantFilter;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\MerchantService;

use App\Support\Repositories\Main\EloquentMainRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

class MerchantServiceRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(MerchantService $model)
    {
        parent::__construct($model);
    }

    public function fetch_merchant_service(array $cols = ['*'],array $data=[], array $relations = [],array $condition = [], string $order = 'asc', int $paginate = 10): CursorPaginator
    {

       return $this->modal
            ->select($cols)
            ->with($relations)
            ->active()
            ->where($condition)
            ->getServiceCategory($data["category"],$data["branch"])
            ->orderBy('id',$order)
            ->cursorPaginate($paginate);
    }

    public function getByMerchantId(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder()->where('merchant_id', $merchant_id);
    }


    public function first(int $merchant_id)
    {
        return $this->modal->where('merchant_id', $merchant_id)->first();
    }

    public function allWithTrashed(array $cols = ['*'], array $relations = [],array $condition = [], string $order = 'asc', string $orderCol = 'id'): Collection
    {
        return $this->builder($cols, $relations,$condition, $order,$orderCol)->withTrashed()->get();
    }

}
