<?php

namespace App\Support\Services\User\PasswordReset;

use App\Support\Services\OtpServices\GenerateOTP;
use App\Support\Services\OtpServices\SendEmailOTP;
use App\Support\Services\OtpServices\SendSmsOTP;
use App\Support\Enum\OTPTypes;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class DetectResetMethod
{
    private SendEmailOTP $sendEmailOTP;
    private SendSmsOTP $sendSmsOTP;
    private GenerateOTP $generateOtpService;

    public function __construct(SendEmailOTP $sendEmailOTP,SendSmsOTP $sendSmsOTP,GenerateOTP $generateOtpService)
    {
        $this->sendEmailOTP = $sendEmailOTP;
        $this->sendSmsOTP = $sendSmsOTP;
        $this->generateOtpService = $generateOtpService;
    }

    /**
     * @param array|string $detection_data
     * @param Model|Collection $model
     * @param OTPTypes $type
     * @return mixed
     */
    public function send(array|string $detection_data , Model|Collection $model, OTPTypes $type): mixed
    {
        try{
            $reset_method = $this->getDetectionType($detection_data);

            $code = $this->generateOtpService->generate(get_class($model), $model->id, $reset_method, $type)?->code;

            if($reset_method == 'email') $this->sendEmailOTP->send($model, $code);
            if($reset_method == 'phone') $this->sendSmsOTP->send($model, $code);

        }catch (Exception $e)
        {
            Log::error($e);
            return false;
        }
        return true;
    }

    public function check(array|string $detection_data , Model $model,string $otp, OTPTypes $type): bool
    {
        $reset_method = $this->getDetectionType($detection_data);

        return (bool) $this->generateOtpService->checkExists(get_class($model), $model->id, $otp,$reset_method, $type );
    }

    public function checkAndExpire(array|string $detection_data , Model $model,string $otp, OTPTypes $type): bool
    {
        $reset_method = $this->getDetectionType($detection_data);

        return (bool) $this->generateOtpService->check(get_class($model), $model->id, $otp,$reset_method, $type );
    }

    /**
     * @param array|string $detection_data
     * @return string
     */
    public function getDetectionType(array|string $detection_data): string
    {
        return is_numeric($detection_data) || $detection_data == 'phone' ? 'phone' : 'email';
    }
}
