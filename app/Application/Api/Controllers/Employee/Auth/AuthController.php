<?php

namespace App\Application\Api\Controllers\Employee\Auth;
use App\Application\Api\Requests\Employee\Auth\LoginRequest;
use App\Domain\Employee\Actions\Auth\DetectEmployeeAction;
use App\Domain\Employee\Resources\EmployeeResource;
use App\Domain\Notification\Actions\SendIDeviceTokenToDB;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request, DetectEmployeeAction $detectEmployeeAction, SendIDeviceTokenToDB $deviceTokenToDB): JsonResponse
    {
        $data = $request->validated();
        unset($data["device_token"]);
        unset($data["platform"]);

        $employee = $detectEmployeeAction->handle($data);
        if (!$employee) return $this->errorResponse(401, __('auth.failed'));
        if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($employee, $request->validated());
        if ($employee->status != "active") return $this->errorResponse(401, __('auth.employee_not_active'));

        return $this->responseData([
            'token' => $employee->createToken('employee_token')->plainTextToken,
            'employee' => new EmployeeResource($employee->load('branch')),
        ], 200);
    }

}
