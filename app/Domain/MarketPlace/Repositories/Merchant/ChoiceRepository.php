<?php

namespace App\Domain\MarketPlace\Repositories\Merchant;

use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Collection;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\Choice;

class ChoiceRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(Choice $model)
    {
        parent::__construct($model);
    }


    public function get_categories_by_branch(array $cols = ['*'], array $relations = [], array $condition = [], string $order = 'asc', array $ids = []): Collection
    {
        return $this->builder($cols, $relations, $condition, $order)->active()->find($ids);
    }

    public function getCategoriesByMerchantId(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder()->where('merchant_id', $merchant_id);
    }

    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();
    }
}
