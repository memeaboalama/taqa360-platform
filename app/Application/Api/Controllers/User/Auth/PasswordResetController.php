<?php

namespace App\Application\Api\Controllers\User\Auth;

use App\Application\Api\Requests\User\Auth\ForgotPasswordRequest;
use App\Application\Api\Requests\User\Auth\VerifyResetCodeRequest;
use App\Application\Api\Requests\User\Auth\ResetPasswordRequest;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Domain\User\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Mail\Admin\ResetPasswordMail;
use App\Support\Enum\OTPTypes;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    use ApiResponseTrait;

    public function __construct(
        private UserRepository $userRepository,
        private SendOtpAction $sendOtpAction,
        private CheckOtpAction $checkOtpAction,
        private InvalidateOldOtpAction $invalidateOldOtpAction,
    ) {}

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $user = $this->userRepository->findByCols(['email' => $data['email']]);

            if (!$user) {
                return $this->responseError(code: 404, msg: __('User not found'));
            }

            $this->invalidateOldOtpAction->handle($user, OTPTypes::USER_RESET_PASS);
            $code = $this->sendOtpAction->handleForMail($user, OTPTypes::USER_RESET_PASS, 60, sendMail: false);

            try {
                Mail::to($user->email)->send(new ResetPasswordMail($code));
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
        $user = $this->userRepository->findByCols(['email' => $data['email']]);

        if (!$user) {
            return $this->responseError(code: 404, msg: __('User not found'));
        }

        $otpStatus = $this->checkOtpAction->handle($user, OTPTypes::USER_RESET_PASS, $data['code']);
        if (!$otpStatus) {
            return $this->responseData(code: 401, msg: __('OTP is not valid'), status: false);
        }

        $this->invalidateOldOtpAction->handle($user, OTPTypes::USER_RESET_PASS);

        $resetToken = $user->createToken('password-reset-token', ['*'], now()->addMinutes(15))->plainTextToken;

        return $this->responseData([
            'token' => $resetToken,
        ], 200, __('OTP verified successfully.'));
    }

    public function resendResetCode(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userRepository->findByCols(['email' => $data['email']]);

        if (!$user) {
            return $this->responseError(code: 404, msg: __('User not found'));
        }

        $this->invalidateOldOtpAction->handle($user, OTPTypes::USER_RESET_PASS);

        $code = $this->sendOtpAction->handleForMail($user, OTPTypes::USER_RESET_PASS, 60, sendMail: false);
        if ($code === false) {
            return $this->responseError(code: 429, msg: __('Please wait before requesting a new OTP.'));
        }

        try {
            Mail::to($user->email)->send(new ResetPasswordMail($code));
        } catch (\Throwable $e) {
            Log::error('Failed to resend password reset email: ' . $e->getMessage());
        }

        return $this->responseData(code: 201, msg: __('OTP resent to your email.'));
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userRepository->findByCols(['email' => $data['email']]);

        if (!$user) {
            return $this->responseError(code: 404, msg: __('User not found'));
        }

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        return $this->responseData(code: 201, msg: __('Password updated successfully.'));
    }
}
