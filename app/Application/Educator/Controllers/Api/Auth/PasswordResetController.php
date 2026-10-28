<?php

namespace App\Application\Educator\Controllers\Api\Auth;

use App\Application\Educator\Requests\Auth\ForgotPasswordRequest;
use App\Application\Educator\Requests\Auth\VerifyResetCodeRequest;
use App\Application\Educator\Requests\Auth\ResetPasswordRequest;
use App\Http\Controllers\Controller;
use App\Mail\Educator\ResetPasswordMail;
use App\Support\Enum\OTPTypes;
use App\Support\Traits\Api\ApiResponseTraitV2;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
use Domain\Educator\Repositories\EducatorRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    use ApiResponseTraitV2;

    public function __construct(
        private EducatorRepository $educatorRepository,
        private SendOtpAction $sendOtpAction,
        private CheckOtpAction $checkOtpAction,
        private InvalidateOldOtpAction $invalidateOldOtpAction,
    ) {}

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $educator = $this->educatorRepository->findByCols(['email' => $data['email']]);

            if (!$educator) {
                return $this->errorResponse(404, __('Educator not found'));
            }

            $this->invalidateOldOtpAction->handle($educator, OTPTypes::EDUCATOR_RESET_PASS);
            // sendMail:false → only the OTP row is created here; the
            // ResetPasswordMail below is the single email sent.
            $code = $this->sendOtpAction->handleForMail($educator, OTPTypes::EDUCATOR_RESET_PASS, 60, sendMail: false);

            try {
                Mail::to($educator->email)->send(new ResetPasswordMail($code));
            } catch (\Throwable $e) {
                Log::error('Failed to send password reset email: ' . $e->getMessage());
            }

            return $this->responseData(code: 201, msg: __('OTP sent to your email.'));
        } catch (\Throwable $e) {
            Log::error('Password reset forgotPassword error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return $this->errorResponse(500, __('An error occurred. Please try again.'));
        }
    }

    public function verifyResetCode(VerifyResetCodeRequest $request): JsonResponse
    {
        $data = $request->validated();
        $educator = $this->educatorRepository->findByCols(['email' => $data['email']]);

        if (!$educator) {
            return $this->errorResponse(404, __('Educator not found'));
        }

        $otpStatus = $this->checkOtpAction->handle($educator, OTPTypes::EDUCATOR_RESET_PASS, $data['code']);
        if (!$otpStatus) {
            return $this->responseData(code: 401, msg: __('OTP is not valid'), status: false);
        }

        $this->invalidateOldOtpAction->handle($educator, OTPTypes::EDUCATOR_RESET_PASS);

        $resetToken = $educator->createToken('password-reset-token', ['*'], now()->addMinutes(15))->plainTextToken;

        return $this->responseData([
            'token' => $resetToken,
        ], 200, __('OTP verified successfully.'));
    }

    public function resendResetCode(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $educator = $this->educatorRepository->findByCols(['email' => $data['email']]);

        if (!$educator) {
            return $this->errorResponse(404, __('Educator not found'));
        }

        $this->invalidateOldOtpAction->handle($educator, OTPTypes::EDUCATOR_RESET_PASS);

        $code = $this->sendOtpAction->handleForMail($educator, OTPTypes::EDUCATOR_RESET_PASS, 60, sendMail: false);
        if ($code === false) {
            return $this->errorResponse(429, __('Please wait before requesting a new OTP.'));
        }

        try {
            Mail::to($educator->email)->send(new ResetPasswordMail($code));
        } catch (\Throwable $e) {
            Log::error('Failed to resend password reset email: ' . $e->getMessage());
        }

        return $this->responseData(code: 201, msg: __('OTP resent to your email.'));
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $educator = $this->educatorRepository->findByCols(['email' => $data['email']]);

        if (!$educator) {
            return $this->errorResponse(404, __('Educator not found'));
        }

        $educator->update([
            'password' => Hash::make($data['password']),
        ]);

        return $this->responseData(code: 201, msg: __('Password updated successfully.'));
    }
}
