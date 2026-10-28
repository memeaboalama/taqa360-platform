<?php

namespace App\Support\Traits\Models;

use Illuminate\Support\Str;

trait HasUuid
{
    public function getUuidName()
    {
        return 'uuid';
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getUuidName()} = Str::uuid()->toString();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }


}
