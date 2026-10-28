<?php

namespace App\Application\Api\Controllers;

use App\Domain\AppBase\Models\Location\City;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class CitiesController extends Controller
{
    use ApiResponseTrait;
    public function getCities() :JsonResponse
    {
        return $this->responseData([
            'cities' => City::all()
        ]);
    }
}
