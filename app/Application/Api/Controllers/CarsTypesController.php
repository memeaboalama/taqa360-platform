<?php

namespace App\Application\Api\Controllers;

use App\Domain\AppBase\Actions\Car\FetchCarTypesAction;
use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Region;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CarsTypesController extends Controller
{
    use ApiResponseTrait;
    public function fetch(FetchCarTypesAction $fetchCarTypesAction) :JsonResponse
    {
        return $this->responseData([
            'car-types' => $fetchCarTypesAction->handle()
        ]);
    }
}
