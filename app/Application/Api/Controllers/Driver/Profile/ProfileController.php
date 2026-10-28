<?php

namespace App\Application\Api\Controllers\Driver\Profile;

use App\Application\Api\Requests\Driver\Profile\CompleteProfileRequest;
use App\Application\Api\Requests\Driver\Profile\UpdateBankInfoRequest;
use App\Application\Api\Requests\Driver\Profile\UpdateLocationRequest;
use App\Application\Api\Requests\Driver\Profile\UpdateNetworkRequest;
use App\Application\Api\Requests\Driver\Profile\UpdatePasswordRequest;
use App\Application\Api\Requests\Driver\Profile\UpdateProfileRequest;
use App\Domain\AppBase\Actions\Image\SendImageToDB;
use App\Domain\Driver\Resources\DriverBankResource;
use App\Domain\Driver\Resources\DriverResource;
use App\Http\Controllers\Controller;
use App\Support\Enum\FileTypesEnum;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Provider\Repositories\ProviderRepository;
use Illuminate\Http\Request;
use MatanYadaev\EloquentSpatial\Enums\Srid;
use MatanYadaev\EloquentSpatial\Objects\Point;
use App\Domain\MarketPlace\Models\DriverHistory;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    use ApiResponseTrait;

    private SendImageToDB $imageAction;

    public function __construct()
    {

        $this->imageAction = new SendImageToDB();
    }

    public function update(UpdateNetworkRequest $networkRequest): \Illuminate\Http\JsonResponse
    {

        $data = $networkRequest->validated();
        Log::info($data);
        Log::info($data["network_status"]);
        $networkRequest->user()->update(['network_status' => $data["network_status"]]);
        DriverHistory::create(['driver_id'=>$networkRequest->user()->id,'action'=>'NetworkStatus'.$data["network_status"]]);
        return $this->responseData([
            'driver' => new DriverResource($networkRequest->user()),
        ], 200);
    }

    public function update_location(UpdateLocationRequest $locationRequest): \Illuminate\Http\JsonResponse
    {
        $driver_auth_user = auth()->user();
        // $driver_auth_id = $driver_auth_user?->id ?? auth()->id();
        if (!($driver_auth_user && $driver_auth_user instanceof \Domain\Driver\Models\Driver))
            return $this->errorResponse('401', 'authorized driver');
        $data = $locationRequest->validated();
        $data['location'] = new Point($data['location']['latitude'], $data['location']['longitude'], Srid::WGS84->value);
        // $locationRequest->user()->update(['location' => $data['location']]);
        $driver_auth_user->update(['location' => $data['location']]);

        return $this->responseData([
            'driver' => new DriverResource($locationRequest->user()),
        ], 200);
    }

    public function fetch(Request $request): \Illuminate\Http\JsonResponse
    {

        return $this->responseData([
            'driver' => new DriverResource($request->user()),
        ], 200);
    }

    public function bank_info(UpdateBankInfoRequest $bankInfoRequest): \Illuminate\Http\JsonResponse
    {
        $data = $bankInfoRequest->validated();

        $bankInfoRequest->user()->bank_info()->updateOrCreate($data);

        return $this->responseData([
            'bank' => new DriverBankResource($bankInfoRequest->user()?->bank_info),
        ], 200);
    }

    public function change_password(UpdatePasswordRequest $passwordRequest): \Illuminate\Http\JsonResponse
    {
        $data = $passwordRequest->validated();

        $passwordRequest->user()->update(['password' => $data["password"]]);

        return $this->responseData([
            'driver' => new DriverResource($passwordRequest->user()),
        ], 200);
    }


    public function update_info(UpdateProfileRequest $request): \Illuminate\Http\JsonResponse
    {

        $data = $request->validated();

        $request->user()->update($data);
        if (isset($data["image"])) {

            $this->imageAction->handle($request->user(), $data["image"], "upload/driver", FileTypesEnum::PROFILE);
        }


        return $this->responseData([
            'driver' => new DriverResource($request->user()),
        ], 200);
    }

    public function complete(CompleteProfileRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();

        $user = $request->user();

        $data["is_complete"] = false;

        $user->update($data);

        if (isset($data["license_image"])) $this->imageAction->handle($user, $data["license_image"], "upload/driver", FileTypesEnum::LicenseImage);


        if (isset($data["car_image"])) $this->imageAction->handle($user, $data["car_image"], "upload/driver", FileTypesEnum::CarImage);


        if (isset($data["id_image"])) $this->imageAction->handle($user, $data["id_image"], "upload/driver", FileTypesEnum::IdImage);


        return $this->responseData([
            'driver' => new DriverResource($request->user()),
        ], 200);
    }
}
