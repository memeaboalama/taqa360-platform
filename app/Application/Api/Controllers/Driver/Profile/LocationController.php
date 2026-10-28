<?php

namespace App\Application\Api\Controllers\Driver\Profile;

use App\Application\Api\Requests\Driver\Profile\FetchLocationRequest;
use App\Domain\Driver\Actions\Profile\FetchLocationAction;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */

    public function fetch(FetchLocationRequest $request, FetchLocationAction $fetchLocationAction): JsonResponse
    {
        $driver = $fetchLocationAction->handle($request->driver_id);
        return $this->responseData([
            'driver' => [
                'location' => $driver->location,
                'status' => $driver->status,
                'network_status' => $driver->network_status,
                'hasOrderNow' => [
                    'order_id' => $driver->orders()->where('driver_id',$driver->id)->whereNotIn('status', ['pending','completed','cancelled'])->pluck('id')->first(),
                ]
            ]
        ]);
    }
}
