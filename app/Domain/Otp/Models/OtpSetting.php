<?php

namespace App\Domain\Otp\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpSetting extends Model
{
    use HasFactory;

    protected $table = 'otp_setting';

    protected $fillable = [
        'user_name',
        'api_key',
        'sender_id',
        'service_name',
        'default',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    // Relations
}
