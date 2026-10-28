<?php

namespace App\Support\Services\OtpServices;

use App\Contracts\Services\OtpContract;
use App\Domain\Otp\Models\OtpSetting;
use App\Domain\Otp\Repositories\OtpSettingRepository;

use App\Support\Services\SmsServices\Msegat;

class SendSmsOTP implements OtpContract
{
    private $defaultOtp;
    private OtpSettingRepository $otpSettingRepository;
    private Msegat $msegat;

    public function __construct(OtpSettingRepository $otpSettingRepository , Msegat $msegat)
    {
        $this->defaultOtp = $otpSettingRepository->getDefaultOtpSetting();
        $this->otpSettingRepository = $otpSettingRepository;
        $this->msegat = $msegat;
    }

    /**
     * @param $phone_number
     * @param $message
     * @return void
     */
    public function send($phone_number, $message)
    {
        if ($this->defaultOtp?->service_name == 'msegat') {
            return $this->msegat->send($phone_number,$message,$this->defaultOtp);
        }else{
            return $this->msegat->send($phone_number,$message,$this->defaultOtp);

        }

    }


}
