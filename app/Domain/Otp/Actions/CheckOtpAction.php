<?php

namespace App\Domain\Otp\Actions;


use App\Domain\User\Models\User;
use App\Support\Enum\OTPTypes;
use Domain\Otp\Repositories\OtpRepository;
use Illuminate\Database\Eloquent\Model;

class CheckOtpAction
{
    private OtpRepository $otpRepository;

    public function __construct(OtpRepository $otpRepository)
    {
        $this->otpRepository = $otpRepository;
    }

    /**
     * @param Model $model
     * @param OTPTypes $OTPType
     * @return mixed
     */
    public function handle(Model $model , OTPTypes $OTPType, $otp = null): mixed
    {
       return $this->otpRepository->check_otp_is_valid($model,$OTPType, $otp);
    }
}
