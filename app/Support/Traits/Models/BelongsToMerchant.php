<?php

namespace App\Support\Traits\Models;


use App\Support\Scopes\MerchantScope;

trait BelongsToMerchant
{
    protected static function bootBelongsToMerchant(): void
    {

        //get only vendors by default
        static::addGlobalScope(new MerchantScope);

        static::creating(function ($model) {
            if (auth()->guard('merchant')->user() && auth()->guard('merchant')->check()) {
                $model->merchant_id = auth()->guard('merchant')->id();
            }

        });

        static::updating(function ($model) {
            if (auth()->guard('merchant')->user() && auth()->guard('merchant')->check()) {
                $model->merchant_id = auth()->guard('merchant')->id();
            }

        });

    }

}
