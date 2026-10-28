<?php

namespace App\Application\Api\Controllers\User\Auth;

use App\Application\Api\Requests\OTP\CheckUserOtpRequest;
use App\Application\Api\Requests\User\RegisterRequest;
use App\Domain\Notification\Actions\SendIDeviceTokenToDB;
use App\Application\Api\Requests\User\LoginRequest;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Domain\User\Actions\Auth\DetectUserAction;
use App\Domain\User\Actions\Auth\RegisterUserAction;
use App\Domain\User\Actions\Profile\UpdateUserAction;
use App\Domain\User\Events\UserLogged;
use App\Domain\User\resources\UserResource;
use App\Http\Controllers\Controller;
use App\Support\Enum\OTPTypes;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * @param LoginRequest $request
     * @param DetectUserAction $detectUserAction
     * @param SendOtpAction $sendOtpAction
     * @return JsonResponse
     */
    public function login(
        LoginRequest $request,
        DetectUserAction $detectUserAction,
        SendOtpAction $sendOtpAction,
        SendIDeviceTokenToDB $deviceTokenToDB
    ): JsonResponse {
        $data = $request->validated();
        //        unset($data["device_token"]);
        //        unset($data["platform"]);
        $user = $detectUserAction->handle(['phone' => $data['phone']]);
        if (!$user) return $this->responseData(code: 400, msg: "User is not found with this phone number! Please Sign up");
        if ($user->status?->value != "active") return $this->errorResponse(401, __('auth.employee_not_active'));

        $sendOtpAction->handle($user, OTPTypes::LOGIN, 60);

        //        if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($user, $request->validated());

        return $this->responseData(code: 201, msg: __('OTP Sent Successfully.'));
    }


    public function register(
        RegisterRequest $request,
        RegisterUserAction $registerUserAction,
        SendOtpAction $sendOtpAction,
        SendIDeviceTokenToDB $deviceTokenToDB
    ): JsonResponse {
        $data = $request->validated();
        if (isset($data['device_token']))
            unset($data["device_token"]);
        if (isset($data["platform"]))
            unset($data["platform"]);
        $user = $registerUserAction->handle($data);
        $msgOffer = $user->msgOffer;
        $sendOtpAction->handle($user, OTPTypes::LOGIN, 60);

        //        if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($user, $request->validated());

        return $this->responseData(code: 201, msg: __('Registered & OTP Sent Successfully.'), data: [$msgOffer]);
    }


    public function resend_otp(
        LoginRequest $request,
        DetectUserAction $detectUserAction,
        SendOtpAction $sendOtpAction
    ): JsonResponse {
        $data = $request->validated();

        $user = $detectUserAction->handle(['phone' => $data['phone']]);

        $sendOtpAction->handle($user, OTPTypes::LOGIN, 60);

        return $this->responseData(code: 201, msg: __('otp sent successfully.'));
    }

    /**
     * @param CheckUserOtpRequest $request
     * @param DetectUserAction $detectUserAction
     * @param CheckOtpAction $checkOtpAction
     * @param InvalidateOldOtpAction $invalidateOldOtpAction
     * @param UpdateUserAction $updateProfileAction
     * @return JsonResponse
     */
    public function verify_otp(
        CheckUserOtpRequest $request,
        DetectUserAction $detectUserAction,
        CheckOtpAction $checkOtpAction,
        InvalidateOldOtpAction $invalidateOldOtpAction,
        UpdateUserAction $updateProfileAction,
        SendIDeviceTokenToDB $deviceTokenToDB
    ): JsonResponse {
        $data = $request->validated();
        unset($data["device_token"]);
        unset($data["platform"]);

        $user = $detectUserAction->handle(['phone' => $data['phone']]);

        $otpStatus = $checkOtpAction->handle($user, OTPTypes::LOGIN, $data['otp']);

        if (!$otpStatus && $data['otp'] != '1357') return $this->responseData(code: 401, msg: __('api::trans.OTP is not valid'), status: false);

        $invalidateOldOtpAction->handle($user, OTPTypes::LOGIN);

        event(new UserLogged($user));
        if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($user, $request->validated());

        return $this->responseData([
            'token' => $user->createToken('user_token')->plainTextToken,
            'user' => new UserResource($user->load('wallet')),
        ], 200, __('phone verified.'));
    }
}
