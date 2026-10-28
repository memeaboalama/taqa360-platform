<?php

namespace App\Application\Api\Controllers\ElmApp\Driver;

use App\Application\Api\Requests\ElmApp\Driver\FetchDriversRequest;
use App\Application\Api\Requests\ElmApp\Driver\UpdateDriverRequest;
use App\Domain\Admin\Actions\Dashboard\Driver\UpdateDriverAction;
use App\Domain\Driver\Repositories\DriverRepository;
use App\Domain\Driver\Resources\v2\DriverResource;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use App\Domain\ElmApp\Actions\Driver\FetchDriverAction;
use Domain\Driver\Models\Driver;


class DriverController extends Controller
{
    use ApiResponseTrait;

    protected DriverRepository $driverRepository;


    public function __construct(DriverRepository $driverRepository)
    {
        $this->driverRepository = $driverRepository;
    }

    public function fetch(FetchDriversRequest $request, FetchDriverAction $fetchDriverAction)
    {
      $drivers = $fetchDriverAction->handle($request->validated());
      return $this->responseData([
        'drivers' => DriverResource::collection($drivers)
      ]);
    }

    public function updateDriverInfo(UpdateDriverRequest $request): \Illuminate\Http\JsonResponse
    {
        $data = $request->validated();
        unset($data['driver_id']);

        $updateData = array_filter($data, fn($value) => $value !== null);
        $this->driverRepository->update($request->validated()['driver_id'],$updateData);
      
        return $this->responseData(
          code: 200, 
          msg: "Driver Update successfully"
        );
    }


}
