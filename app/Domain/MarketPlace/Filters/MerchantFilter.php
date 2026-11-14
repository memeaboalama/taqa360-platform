<?php

namespace App\Domain\MarketPlace\Filters;

use tiagomichaelsousa\LaravelFilters\QueryFilters;

class MerchantFilter extends QueryFilters
{

    public function __construct($request = null)
    {
        parent::__construct($request);
    }

    public function category($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->whereHas('categories', function ($v)use ($value) {
            $v->where('market_category_id', $value);
        });
    }

    public function keyword($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('company_name->en', 'LIKE', '%' . $value . '%')
            ->orWhere('company_name->ar', 'LIKE', '%' . $value . '%');
    }

    public function menu($value = []): \Illuminate\Database\Eloquent\Builder
    {
        if (count($value) > 0)
        {
            return $this->builder->whereHas('branches', function ($v)use ($value) {
                $v->whereHas('menu', function ($c) use ($value) {
                    $c->whereIn('id', $value);
                });
            });
        }else{
            return $this->builder;
        }
    }


    public function main_category($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', (array) $value), fn ($id) => $id > 0)));

        if ($ids === []) {
            return $this->builder;
        }

        return $this->builder->where(function ($query) use ($ids) {
            foreach ($ids as $id) {
                $query->orWhere('main_categories', (string) $id)
                    ->orWhere('main_categories', 'LIKE', $id . ',%')
                    ->orWhere('main_categories', 'LIKE', '%,' . $id . ',%')
                    ->orWhere('main_categories', 'LIKE', '%,' . $id);
            }
        });
    }
}
