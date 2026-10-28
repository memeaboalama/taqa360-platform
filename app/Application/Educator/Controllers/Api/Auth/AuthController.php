<?php

namespace App\Application\Educator\Controllers\Api\Auth;

use App\Application\Educator\Enums\EducatorStatus;
use App\Application\Educator\Requests\Auth\LoginRequest;
use App\Application\Educator\Requests\Auth\RegisterRequest;
use App\Application\Educator\Requests\Auth\ResendVerificationCodeRequest;
use App\Application\Educator\Requests\Auth\VerifyEmailCodeRequest;
use App\Domain\AppBase\Actions\GeneralSetting\FetchGeneralSettingsAction;
use App\Domain\Educator\Resource\EducatorResource;
use App\Domain\Notification\Actions\SendIDeviceTokenToDB;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTraitV2;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Domain\Otp\Actions\SendOtpAction;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Support\Enum\OTPTypes;
use Domain\Educator\Actions\Auth\DetectEducatorAction;
use Domain\Educator\Actions\Auth\RegisterEducatorAction;
use Domain\Educator\Repositories\EducatorRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponseTraitV2;

    public function login(LoginRequest $request, DetectEducatorAction $detectEducatorAction, SendIDeviceTokenToDB $deviceTokenToDB): JsonResponse
    {
        Log::info("DEFLIJHWLIFKWS");
        $data = $request->validated();
        unset($data["device_token"]);
        unset($data["platform"]);
        Log::info("DEFLIJHWLIFKWS", [$request->all()]);
        $educator = $detectEducatorAction->detectEducatorApi($data);
        Log::info("DEFLIJHWLIFKWS", [$educator]);
        if (!$educator) return $this->errorResponse(401, __('auth.failed'));
        if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($educator, $request->validated());
        // See the note in the Admin AuthController: config('sanctum.expiration', 1440)
        // resolves to null because the key exists with a null value, which produced
        // tokens that were expired the moment they were created.
        $expirationMinutes = config('sanctum.expiration') ?: 1440;

        $data = [
            'user' => new EducatorResource($educator),
            'tokens' => [
                'accessToken' => $educator->createToken('educator-api')->plainTextToken,
                // 'accessToken' => $educator->createToken('educator-api', ['*'], now()->addMinutes($expirationMinutes))->plainTextToken,
                'refreshToken' => $educator->createToken('admin-refresh-token', ['*'], now()->addDays(30))->plainTextToken,
                'expiresAt' => Carbon::now()->addMinutes($expirationMinutes)->toIso8601String(),
            ],
        ];
        Log::info("DEFLIJHWLIFKWS" , $data);

        return $this->responseData($data, 200);
    }

    public function register(RegisterRequest $request, RegisterEducatorAction $registerAction, SendOtpAction $sendOtpAction)
    {
        $validated = $request->validated();

        $educator = $registerAction->handle([
            'name' => "{$validated['first_name']} {$validated['last_name']}",
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        // Temporary switch (config/auth.php → email_verification.enabled, driven
        // by EMAIL_VERIFICATION_ENABLED in .env). When false the "confirm your
        // email" flow is suspended: no code is emailed and the account is marked
        // verified immediately. Flip it back to true to restore the flow.
        //
        // ---------------------------------------------------------------------------
        // [DISABLED - email verification, comment-only] The whole switch below is
        // commented out so registration no longer depends on the environment variable:
        // no confirmation code is emailed and the account is activated immediately.
        // The original `if / else` is kept commented (not deleted) - uncomment it and
        // re-comment the temporary replacement to restore the flow.
        // ---------------------------------------------------------------------------
        // $verificationEnabled = (bool) config('auth.email_verification.enabled', true);

        // if ($verificationEnabled) {
        //     // Create the OTP and email it. throwOnFailure makes a broken SMTP
        //     // surface as a real error instead of a misleading "code sent" success.
        //     try {
        //         $sendOtpAction->handleForMail($educator, OTPTypes::EDUCATOR_VERIFY_EMAIL, 120, throwOnFailure: true);
        //     } catch (\Throwable $e) {
        //         Log::error('Failed to send educator verification email on register: ' . $e->getMessage());
        //         return $this->errorResponse(500, __('We could not send the verification code to your email. Please try again.'));
        //     }
        // } else {
        //     $educator->forceFill([
        //         'email_verified_at' => now(),
        //         'status' => EducatorStatus::ACTIVE,
        //     ])->save();
        // }

        // [TEMPORARY REPLACEMENT - email verification disabled] mark verified + active
        // right away so the client is never asked for a confirmation code.
        $educator->forceFill([
            'email_verified_at' => now(),
            'status' => EducatorStatus::ACTIVE,
        ])->save();

        // See the note in the Admin AuthController: config('sanctum.expiration', 1440)
        // resolves to null because the key exists with a null value, which produced
        // tokens that were expired the moment they were created.
        $expirationMinutes = config('sanctum.expiration') ?: 1440;

        $data = [
            'user' => new EducatorResource($educator),
            // [DISABLED - email verification, comment-only] the client was told to
            // require verification whenever the env switch was on; hard-coded to false
            // while verification is suspended. Restore `=> $verificationEnabled` together
            // with the commented block above.
            // 'requires_email_verification' => $verificationEnabled,
            'requires_email_verification' => false,
            'tokens' => [
                'accessToken' => $educator->createToken('educator-api')->plainTextToken,
                // 'accessToken' => $educator->createToken('educator-api', ['*'], now()->addMinutes($expirationMinutes))->plainTextToken,
                'refreshToken' => $educator->createToken('admin-refresh-token', ['*'], now()->addDays(30))->plainTextToken,
                'expiresAt' => Carbon::now()->addMinutes($expirationMinutes)->toIso8601String(),
            ],
        ];
        return $this->responseData($data, 200);
    }

    /**
     * Confirm the email address of an educator with the 6-digit code that was
     * emailed on registration. Contract: { email, code } — the same field names
     * the front-end sends, and the same OTP type used to create and resend it.
     */
    public function verifyEmailCode(
        VerifyEmailCodeRequest $request,
        CheckOtpAction $checkOtpAction,
        InvalidateOldOtpAction $invalidateOldOtpAction,
        EducatorRepository $educatorRepository
    ): JsonResponse {
        $data = $request->validated();

        $educator = $educatorRepository->findByCols(['email' => $data['email']]);

        if (!$educator) {
            return $this->errorResponse(404, __('Educator not found'));
        }

        $otp = $checkOtpAction->handle($educator, OTPTypes::EDUCATOR_VERIFY_EMAIL, $data['code']);

        if (!$otp) {
            return $this->errorResponse(422, __('Invalid or expired verification code.'));
        }

        $educator->forceFill([
            'email_verified_at' => now(),
            'status' => EducatorStatus::ACTIVE,
        ])->save();

        $invalidateOldOtpAction->handle($educator, OTPTypes::EDUCATOR_VERIFY_EMAIL);

        return $this->responseData([
            'email' => $educator->email,
            'email_verified_at' => $educator->email_verified_at?->toIso8601String(),
        ], 200, __('Email verified successfully.'));
    }

    /**
     * Re-send the verification code. Unknown emails get the very same success
     * response so the endpoint cannot be used to enumerate accounts.
     */
    public function resendOtp(ResendVerificationCodeRequest $request, SendOtpAction $sendOtpAction, EducatorRepository $educatorRepository): JsonResponse
    {
        $data = $request->validated();

        $educator = $educatorRepository->findByCols(['email' => $data['email']]);

        if (!$educator) {
            return $this->responseData(code: 201, msg: __('If the account exists, a verification code has been sent.'));
        }

        try {
            $sendOtpAction->handleForMail($educator, OTPTypes::EDUCATOR_VERIFY_EMAIL, 120, throwOnFailure: true);
        } catch (\Throwable $e) {
            Log::error('Failed to resend educator verification email: ' . $e->getMessage());
            return $this->errorResponse(500, __('We could not send the verification code to your email. Please try again.'));
        }

        return $this->responseData(code: 201, msg: __('Verification code sent to your email.'));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => __('educator::auth.alerts.logout_success'),
        ]);
    }
}
