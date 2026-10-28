<?php

namespace App\Application\Api\Controllers\ElmApp\Auth;
use App\Application\Api\Requests\ElmApp\Auth\LoginRequest;
use App\Domain\Admin\Actions\Dashboard\Auth\DetectAdminAction;
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
    public function login(LoginRequest $request, DetectAdminAction $detectAdminAction, SendIDeviceTokenToDB $deviceTokenToDB): JsonResponse
    {
        
        $data = $request->validated();
        unset($data["device_token"]);
        unset($data["platform"]);

        $admin = $detectAdminAction->detectAdminApi($data);
        if (!$admin) return $this->errorResponse(401, __('auth.failed'));
        if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($admin, $request->validated());

        return $this->responseData([
            'token' => $admin->createToken('admin-api')->plainTextToken,
        ], 200);
    }

}
