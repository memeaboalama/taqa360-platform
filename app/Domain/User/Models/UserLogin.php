<?php

namespace App\Domain\User\Models;

use App\Support\Traits\Models\BelongsToUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    use HasFactory,BelongsToUser;

    protected $fillable = ['user_id','logged_in_at'];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'logged_in_at'  =>  'datetime'
    ];

}
