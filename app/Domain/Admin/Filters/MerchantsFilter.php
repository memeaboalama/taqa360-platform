<?php

namespace App\Domain\Admin\Filters;

use tiagomichaelsousa\LaravelFilters\QueryFilters;

class MerchantsFilter extends QueryFilters
{
    public function merchant_id($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('merchant_id', $value);
    }

    public function status($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('status', $value);
    }

    public function merchant_branch_id($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        return $this->builder->where('merchant_branch_id', $value);
    }

    public function date_condition($value = ''): \Illuminate\Database\Eloquent\Builder
    {
        if ($value == "equal")
            return $this->builder->whereDate('created_at', request()->date);

        if ($value == "gt")
            return $this->builder->whereDate('created_at','>=', request()->date);

        if ($value == "between")
            return $this->builder->whereDate('created_at','>=',request()->date)
                ->whereDate('created_at','<=', request()->to_date);

        return $this->builder;
    }
}
