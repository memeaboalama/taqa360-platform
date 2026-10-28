<?php

namespace App\Support\Traits\Models;


use App\Domain\Otp\Models\Otp;

trait HasOtp
{

    public function otps()
    {
        return $this->morphMany(Otp::class, 'otpable');
    }
}
