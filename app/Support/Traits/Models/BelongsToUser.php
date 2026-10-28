<?php

namespace App\Support\Traits\Models;

use App\Domain\User\Models\User;
use Illuminate\Support\Str;

trait BelongsToUser
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
