<?php

namespace App\Application\Admin\Controllers\Api\Auth;

use App\Application\Admin\Requests\Api\Auth\ForgotPasswordRequest;
use App\Application\Admin\Requests\Api\Auth\VerifyResetCodeRequest;
use App\Application\Admin\Requests\Api\Auth\ResetPasswordRequest;
use Domain\Admin\Repositories\AdminRepository;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
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
        private AdminRepository $adminRepository,
        private SendOtpAction $sendOtpAction,
        private CheckOtpAction $checkOtpAction,
        private InvalidateOldOtpAction $invalidateOldOtpAction,
    ) {}

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $admin = $this->adminRepository->findByCols(['email' => $data['email']]);

            if (!$admin) {
                return $this->responseError(code: 404, msg: __('Admin not found'));
            }

            $this->invalidateOldOtpAction->handle($admin, OTPTypes::ADMIN_RESET_PASS);
            $code = $this->sendOtpAction->handleForMail($admin, OTPTypes::ADMIN_RESET_PASS, 60, sendMail: false);

            try {
                Mail::to($admin->email)->send(new ResetPasswordMail($code));
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
        $admin = $this->adminRepository->findByCols(['email' => $data['email']]);

        if (!$admin) {
            return $this->responseError(code: 404, msg: __('Admin not found'));
        }

        $otpStatus = $this->checkOtpAction->handle($admin, OTPTypes::ADMIN_RESET_PASS, $data['code']);
        if (!$otpStatus) {
            return $this->responseData(code: 401, msg: __('OTP is not valid'), status: false);
        }

        $this->invalidateOldOtpAction->handle($admin, OTPTypes::ADMIN_RESET_PASS);

        $resetToken = $admin->createToken('password-reset-token', ['*'], now()->addMinutes(15))->plainTextToken;

        return $this->responseData([
            'token' => $resetToken,
        ], 200, __('OTP verified successfully.'));
    }

    public function resendResetCode(ForgotPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $admin = $this->adminRepository->findByCols(['email' => $data['email']]);

        if (!$admin) {
            return $this->responseError(code: 404, msg: __('Admin not found'));
        }

        $this->invalidateOldOtpAction->handle($admin, OTPTypes::ADMIN_RESET_PASS);

        $code = $this->sendOtpAction->handleForMail($admin, OTPTypes::ADMIN_RESET_PASS, 60, sendMail: false);
        if ($code === false) {
            return $this->responseError(code: 429, msg: __('Please wait before requesting a new OTP.'));
        }

        try {
            Mail::to($admin->email)->send(new ResetPasswordMail($code));
        } catch (\Throwable $e) {
            Log::error('Failed to resend password reset email: ' . $e->getMessage());
        }

        return $this->responseData(code: 201, msg: __('OTP resent to your email.'));
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $admin = $this->adminRepository->findByCols(['email' => $data['email']]);

        if (!$admin) {
            return $this->responseError(code: 404, msg: __('Admin not found'));
        }

        $admin->update([
            'password' => Hash::make($data['password']),
        ]);

        return $this->responseData(code: 201, msg: __('Password updated successfully.'));
    }
}
