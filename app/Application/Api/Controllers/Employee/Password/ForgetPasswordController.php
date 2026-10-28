<?php

namespace App\Application\Api\Controllers\Employee\Password;

use App\Application\Api\Requests\Employee\Auth\SendOtpRequest;
use App\Application\Api\Requests\Employee\Password\ResetPasswordRequest;
use App\Application\Api\Requests\OTP\CheckEmployeeOtpRequest;
use App\Application\Api\Requests\User\LoginRequest;
use App\Domain\Employee\Actions\Auth\DetectEmployeeAction;
use App\Domain\Employee\Actions\Auth\UpdatePasswordAction;
use App\Domain\Employee\Resources\EmployeeResource;
use App\Domain\Notification\Actions\SendIDeviceTokenToDB;
use App\Domain\Otp\Actions\CheckOtpAction;
use App\Domain\Otp\Actions\InvalidateOldOtpAction;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Http\Controllers\Controller;
use App\Support\Enum\OTPTypes;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ForgetPasswordController extends Controller
{
    use ApiResponseTrait;

    public function forget(SendOtpRequest $request, DetectEmployeeAction $detectEmployeeAction, SendOtpAction $sendOtpAction): JsonResponse
    {
        $data = $request->validated();
        $employee = $detectEmployeeAction->getByPhone($data);
        if (!$employee)
        {
            return $this->responseError(code: 404, msg: __('Employee not found'));
        }
        $sendOtpAction->handle($employee, OTPTypes::LOGIN, 60);
        return $this->responseData(code: 201, msg: __('OTP Sent Successfully.'));
    }

    public function verify(CheckEmployeeOtpRequest $request,
                           DetectEmployeeAction $detectEmployeeAction,
                           CheckOtpAction $checkOtpAction,
                           InvalidateOldOtpAction $invalidateOldOtpAction,
                           SendIDeviceTokenToDB $deviceTokenToDB): JsonResponse
    {
        $data = $request->validated();
        unset($data["device_token"]);
        unset($data["platform"]);

        $employee = $detectEmployeeAction->getByPhone($data);

        $otpStatus = $checkOtpAction->handle($employee, OTPTypes::LOGIN, $data['otp']);
        if (!$otpStatus) return $this->responseData(code: 401, msg: __('api::trans.OTP is not valid'), status: false);

        $invalidateOldOtpAction->handle($employee, OTPTypes::LOGIN);

        Auth::guard('merchant_employee')->login($employee);
        if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($employee, $request->validated());

        return $this->responseData([
            'token' => $employee->createToken('employee_token')->plainTextToken,
            'employee' => new EmployeeResource($employee),
        ], 200, __('phone verified.'));
    }

    public function reset(ResetPasswordRequest $request, UpdatePasswordAction $updatePasswordAction): JsonResponse
    {
        $data = $request->validated();
        $updatePasswordAction->handle($data);
        return $this->responseData(code: 201, msg: __('Password Updated Successfully.'));
    }
}
