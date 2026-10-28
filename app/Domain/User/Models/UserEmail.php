<?php

namespace App\Domain\User\Models;

use App\Support\Traits\Models\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserEmail extends Model
{
    use HasFactory,BelongsToUser,SoftDeletes;

    protected $fillable = [
        'user_id',
        'email',
        'is_default',
    ];

    protected $casts = [
        'verified_at'=>'datetime',
    ];

}

