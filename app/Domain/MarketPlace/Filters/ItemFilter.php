<?php

namespace App\Domain\MarketPlace\Filters;

use tiagomichaelsousa\LaravelFilters\QueryFilters;

class ItemFilter extends QueryFilters
{

    public function __construct($request = null)
    {
        parent::__construct($request);
    }

    public function category($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        if (!$value && $value === 'all') {
            return $this->builder;
        } else {
            if (str_contains(',', $value)) {
                $ids =  explode(',', $value);
                return $this->builder->whereIn('category_id', $ids)->orWhereIn('main_category_id', $ids)->orWhereIn('sub_category_id', $ids)->orWhereIn('child_category_id', $ids);
            }
            return $this->builder->whereHas('category', function ($v) use ($value) {
                $v->where('title->en', 'LIKE', '%' . $value . '%')
                    ->orWhere('title->ar', 'LIKE', '%' . $value . '%');
            })->orWhereHas('mainCategory', function ($v) use ($value) {
                $v->where('title->en', 'LIKE', '%' . $value . '%')
                    ->orWhere('title->ar', 'LIKE', '%' . $value . '%');
            })
                ->orWhereHas('subCategory', function ($v) use ($value) {
                    $v->where('title->en', 'LIKE', '%' . $value . '%')
                        ->orWhere('title->ar', 'LIKE', '%' . $value . '%');
                })
                ->orWhereHas('childCategory', function ($v) use ($value) {
                    $v->where('title->en', 'LIKE', '%' . $value . '%')
                        ->orWhere('title->ar', 'LIKE', '%' . $value . '%');
                });
        }
    }

    public function keyword($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('title->en', 'LIKE', '%' . $value . '%')
            ->orWhere('title->ar', 'LIKE', '%' . $value . '%');
    }

    public function search($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('title->en', 'LIKE', '%' . $value . '%')
            ->orWhere('title->ar', 'LIKE', '%' . $value . '%');
    }

    public function q($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('title->en', 'LIKE', '%' . $value . '%')
            ->orWhere('title->ar', 'LIKE', '%' . $value . '%');
    }

    public function category_id($value = []): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereIn('category_id', $value)->orWhereIn('main_category_id', $value)->orWhereIn('sub_category_id', $value)->orWhereIn('child_category_id', $value);
    }


    public function menu($value = []): \Illuminate\Database\Eloquent\Builder
    {
        if (count($value) > 0) {
            return $this->builder->whereHas('category', function ($v) use ($value) {
                $v->whereHas('services', function ($c) use ($value) {
                    $c->whereIn('id', $value);
                });
            });
        } else {
            return $this->builder;
        }
    }


    public function main_category($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        if (is_null($value))
            return $this->builder;
        else if (is_array($value) && !empty($value))
            return $this->builder->where('main_categories', 'LIKE', '%' . implode(',', $value) . '%');
        else if (is_string($value))
            return $this->builder->where('main_categories', 'LIKE', '%' . $value . '%');
        else {
            return $this->builder;
        }
    }
}
