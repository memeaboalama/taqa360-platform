<?php

namespace App\Support\Traits\Models;

use App\Support\Enum\StatusEnum;
use Illuminate\Support\Str;

trait HasStatus
{
    public function scopeActive($query)
    {
        return $query->where('status',StatusEnum::ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status',StatusEnum::INACTIVE);
    }
}
