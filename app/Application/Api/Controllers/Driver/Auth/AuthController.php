<?php

namespace App\Application\Api\Controllers\Driver\Auth;

use App\Application\Api\Requests\Driver\Auth\LoginRequest;
use App\Application\Api\Requests\Driver\Auth\RegisterRequest;
use App\Domain\Driver\Actions\Auth\RegisterDriverAction;
use App\Domain\Driver\Resources\DriverResource;
use App\Domain\Employee\Actions\Auth\DetectEmployeeAction;
use App\Domain\Employee\Resources\EmployeeResource;
use App\Domain\Notification\Actions\SendIDeviceTokenToDB;
use App\Domain\Otp\Actions\SendOtpAction;
use App\Http\Controllers\Controller;
use App\Support\Enum\OTPTypes;
use App\Support\Enum\StatusEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Driver\Models\Driver;
use Domain\Notification\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Domain\MarketPlace\Models\DriverHistory;

class AuthController extends Controller
{
    use ApiResponseTrait;

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request, SendIDeviceTokenToDB $deviceTokenToDB): JsonResponse
    {
        $data = $request->validated();
        unset($data["device_token"]);
        unset($data["platform"]);

        if (isset($data['location'])) {
            $data['location'] = new Point($data['location']['latitude'], $data['location']['longitude'], Srid::WGS84->value);
        }



        $driver = Driver::query()->whereEmail($data["email"])->first();

        if ($driver) {
            if (!Auth::guard('driver')->attempt(request()->only('email', 'password'))) return $this->errorResponse('500', 'email/password not correct');

            if (isset($data['location'])) {
                $driver->update(['location' => $data['location']]);
            }

            DriverHistory::create(['driver_id'=>$driver["id"],'action'=>'Enter']);
        }
        $data['status'] = StatusEnum::INACTIVE->value;

        // if (!$driver) $driver = Driver::query()->create($data);
        if (!$driver) return $this->responseData(code: 400, msg: "User is not found with this Email ! Please Sign up");


        // if (isset($request->validated()["device_token"])) $deviceTokenToDB->handle($driver, $request->validated());
        if (isset($request->validated()["device_token"])) {
            if ($driver->device_tokens() && $driver->device_tokens()->exists())
                $driver->device_tokens()->delete();

            $deviceTokenToDB->handle($driver, $request->validated());
        }
        if ($driver->tokens() && $driver->tokens()->exists())
            $driver->tokens()->delete();

        return $this->responseData([
            'token' => $driver->createToken('driver_token')->plainTextToken,
            'driver' => new DriverResource($driver),
        ], 201);
    }


    public function register(
        RegisterRequest $registerRequest,
        RegisterDriverAction $registerDriverAction,
        SendOtpAction $sendOtpAction
    ): JsonResponse {
        $data = $registerRequest->all();
        DB::beginTransaction();
        $driver = $registerDriverAction->handle($data);
        //        $sendOtpAction->handle($driver, OTPTypes::LOGIN, 60);
        DB::commit();

        return $this->responseData(code: 201, msg: __('Registered Successfully.'));
    }

    public function remove_token(Request $request): JsonResponse
    {
        $deviceTokens = DeviceToken::query()
            ->where('token', $request->device_token)
            ->where('deviceable_type', Driver::class);

        $deviceToken = $deviceTokens->first();

        if ($deviceToken) {
            DriverHistory::create(['driver_id' => $deviceToken->deviceable_id, 'action' => 'Exit']);
            $deviceTokens->delete();
        }

        return $this->responseData([], 200, 'success');
    }
}
