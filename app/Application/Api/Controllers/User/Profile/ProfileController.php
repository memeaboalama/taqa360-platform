<?php

namespace App\Application\Api\Controllers\User\Profile;

use App\Application\Api\Requests\OTP\CheckUserOtpRequest;
use App\Application\Api\Requests\User\ProfileRequest;
use App\Application\Api\Requests\User\ProfileRequestV2;
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

class ProfileController extends Controller
{
    use ApiResponseTrait;


    public function index(): JsonResponse
    {

        return $this->responseData([new UserResource(auth()->user()->load('wallet'))]);
    }

    public function update(ProfileRequest $request): JsonResponse
    {
        $data = $request->validated();
        auth()->user()->update($data);
        return $this->responseData([new UserResource(auth()->user()->load('wallet'))]);
    }
    public function updateV2(ProfileRequestV2 $request): JsonResponse
    {
        $data = $request->validated();
        auth()->user()->update($data);
        return $this->responseData([new UserResource(auth()->user()->load('wallet'))]);
    }
}
