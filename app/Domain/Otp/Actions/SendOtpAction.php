<?php

namespace App\Domain\Otp\Actions;


use App\Domain\Otp\Jobs\QueueSmsOtp;
use App\Domain\Otp\Jobs\QueueSmsOffer;
use App\Domain\User\Models\User;
use App\Mail\Marketplace\ConfirmMail;
use App\Support\Enum\OTPTypes;
use App\Support\Services\OtpServices\SendSmsOTP;
use Domain\Otp\Repositories\OtpRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOtpAction
{
    private OtpRepository $otpRepository;

    public function __construct(OtpRepository $otpRepository)
    {
        $this->otpRepository = $otpRepository;
    }

    /**
     * @param Model $model
     * @param OTPTypes $OTPType
     * @param int $expired_in_minutes
     * @return mixed
     */
    public function handle(Model $model , OTPTypes $OTPType, int $expired_in_minutes): mixed
    {
        $code = rand(1000,9999);
        $payload = [
            'code'          =>  $code,
            'type'          =>  $OTPType,
            'expired_at'    =>  now()->addMinutes($expired_in_minutes)
        ];

        $this->otpRepository->mark_previous_codes_as_expired($model,$OTPType);

        dispatch(new QueueSmsOtp(model:$model,message: __("your otp number is :").$code));

        return $this->otpRepository->createForModel($model,$payload);
    }


    /**
     * @param Model $model
     * @param OTPTypes $OTPType
     * @param int $expired_in_minutes
     * @param bool $throwOnFailure When true, an SMTP failure throws so the
     *                            caller can return a real error instead of a
     *                            misleading "code sent" success.
     * @param bool $sendMail When false, only the OTP row is created and no
     *                       email is sent (for callers that send their own
     *                       template, e.g. ResetPasswordMail).
     * @return mixed
     */
    public function handleForMail(Model $model , OTPTypes $OTPType, int $expired_in_minutes, bool $throwOnFailure = false, bool $sendMail = true): mixed
    {
        $code = rand(100000,999999);
        $payload = [
            'code'          =>  $code,
            'type'          =>  $OTPType,
            'expired_at'    =>  now()->addMinutes($expired_in_minutes)
        ];

        $this->otpRepository->mark_previous_codes_as_expired($model,$OTPType);
        $this->otpRepository->createForModel($model,$payload);

        if (!$sendMail) {
            return $code;
        }

        // Send synchronously so the code actually arrives even when no
        // queue worker is running (QUEUE_CONNECTION=database leaves jobs
        // pending forever with 0 attempts). Queueing only is what caused
        // "رمز الكود لا يصل" on register / resend verification.
        try {
            Mail::to($model->email)->send(new ConfirmMail($code));
        } catch (\Throwable $e) {
            Log::error('SendOtpAction: Failed to send OTP email to ' . $model->email . ': ' . $e->getMessage());
            if ($throwOnFailure) {
                throw $e;
            }
        }

        return $code;
    }
}
