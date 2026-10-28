<?php

namespace App\Domain\Otp\Models;

use App\Support\Enum\OTPStatus;
use App\Support\Enum\OTPTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Otp extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'expired_at', 'deleted_at', 'otpable_id', 'otpable_type', 'type', 'status'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type'  =>  OTPTypes::class,
        'status'  =>  OTPStatus::class,
    ];

    protected $attributes = [
        'status' => OTPStatus::ACTIVE,
        'expired_at' => 'datetime',
    ];

    // Relations

    public function otpable(): MorphTo
    {
        return $this->morphTo();
    }
}
