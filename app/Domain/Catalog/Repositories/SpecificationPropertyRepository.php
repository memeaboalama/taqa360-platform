<?php

namespace App\Domain\Catalog\Repositories;

use Domain\Catalog\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Collection;
use App\Support\Repositories\Main\EloquentMainRepository;
use App\Domain\Catalog\Models\SpecificationProperty;

class ItemSpecificationPropertyRepository extends EloquentMainRepository
{
    /*
    * replace Model class param with your Repository Model
    */

    public function __construct(SpecificationProperty $model)
    {
        parent::__construct($model);
    }

    public function get_categories_by_branch(array $cols = ['*'], array $with = [], array $condition = [], string $sortDir = 'asc', array $ids = []): Collection
    {
        return $this->builder(cols: $cols,relations: $with,condition: $condition, order: $sortDir)->active()->find($ids);
    }

    public function get_item_specification_property(array $cols = ['*'], array $with = [], array $condition = [], array $data = []): Collection
    {
        return $this->builder(cols: $cols,relations: $with,condition: $condition)->active()->find($data['id']);
    }

    public function get_item_specification_properties_by_item_specification_id(int $item_specification_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder()->where('specification_id', $item_specification_id);
    }
    public function get_item_specification_properties_by_item_id(int $item_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder()->where('item_id', $item_id)->orWhereHas('specification', function($q) use($item_id){  $q->where('item_id',$item_id);});
    }
    public function get_item_specification_properties_by_merchant_id(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        // return $this->builder()-whereHas('item',->where('merchant_id', $merchant_id);
        // return $this->builder()-whereHas('item', fn($q) =>  $q->where('merchant_id','like', '%'.$merchant_id.'%'));
        // return $this->builder()->whereHas('item', fn($q) =>  $q->where('merchant_id',$merchant_id));
        return $this->builder()->whereHas('item', function($q) use($merchant_id){  $q->where('merchant_id',$merchant_id);})
        ->orWhereHas('specification', function($qi) use($merchant_id){  $qi->whereHas('item', function($q) use($merchant_id){  $q->where('merchant_id',$merchant_id);});})
        ;
    }

    public function get_all_item_specification_properties_as_builder(array $cols, array $with): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder(cols: $cols,relations: $with,condition: [],order: 'desc');
    }

    public function count(array $filter)
    {
        return $this->modal->where($filter)->count();
    }
}
