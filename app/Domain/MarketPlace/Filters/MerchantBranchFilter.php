<?php

namespace App\Domain\MarketPlace\Filters;

use tiagomichaelsousa\LaravelFilters\QueryFilters;

class MerchantBranchFilter extends QueryFilters
{
    public function keyword($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder
            ->where('merchants.company_name->en', 'LIKE', '%' . $value . '%')
            ->orWhere('merchants.company_name->ar', 'LIKE', '%' . $value . '%');
    }

    public function category($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        if ($value != "")
        {
            return $this->builder->join('market_place_category_merchants AS categories', function($join) use($value)
            {
                $join->on('merchants.id', '=', 'categories.merchant_id')
                    ->where('categories.market_category_id', $value);
            });
        }else{
            return $this->builder;
        }
    }

    public function menu($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        if (count($value) > 0)
        {
            return $this->builder->join('merchant_services_category', function($join) use($value)
            {
                $join->on('merchant_branches.merchant_service_id', '=', 'merchant_services_category.merchant_service_id')
                    ->whereIn('merchant_services_category.merchant_service_id', $value);
            });
        }else{
            return $this->builder;
        }
    }

    public function main_category($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('merchants.main_categories', 'LIKE', '%' . implode(',', $value) . '%');
    }
}
