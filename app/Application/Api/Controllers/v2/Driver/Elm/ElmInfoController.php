<?php

namespace App\Application\Api\Controllers\v2\Driver\Elm;

use App\Application\Api\Requests\Driver\Elm\UpdateElmInfoRequest;
use App\Domain\Driver\Resources\v2\DriverResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Domain\Driver\Models\Driver;
use Illuminate\Http\Request;



class ElmInfoController extends Controller
{
    use ApiResponseTrait;

    public function update(UpdateElmInfoRequest $updateElmInfoRequest): \Illuminate\Http\JsonResponse
    {

        $data = $updateElmInfoRequest->validated();
        $updateData = array_filter($data, fn($value) => $value !== null);
        $updateElmInfoRequest->user()->update($updateData);
        return $this->responseData([
            'driver' => new DriverResource($updateElmInfoRequest->user()),
        ], 200);
    }

    public function fetch(Request $request): \Illuminate\Http\JsonResponse
    {

        return $this->responseData([
            'driver' => new DriverResource($request->user()),
        ], 200);

    }
}
