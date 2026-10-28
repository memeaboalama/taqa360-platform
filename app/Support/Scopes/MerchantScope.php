<?php

namespace App\Support\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;


class MerchantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): void
    {
        if(!request()->is('api/*')){
            if (Auth::hasUser()) {
                if(auth()->guard('merchant')->user() && auth()->guard('merchant')->check()){
                    $builder->where('merchant_id', '=', auth()->guard('merchant')->id());
                }
            }
        }


    }
}
