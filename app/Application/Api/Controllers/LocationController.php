<?php

namespace App\Application\Api\Controllers;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Country;
use App\Domain\AppBase\Models\Location\Region;
use App\Http\Controllers\Controller;
use App\Support\Traits\Api\ApiResponseTrait;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    use ApiResponseTrait;

    public function getCountries() :JsonResponse
    {
        $coutries = Country::where('status',1)->get()->map(function($country){
            return [
                'id'    =>  $country->uuid,
                'title' =>  $country->title
            ];
        });

        return $this->responseData([
            'countries' => $coutries
        ]);
    }

    public function getCountryCities(Country $country) :JsonResponse
    {
        return $this->responseData([
            'cities' => $country->cities()->where('status',1)->get()->map(function($city){
                return [
                    'id'    =>  $city->uuid,
                    'title' =>  $city->title
                ];
            })
        ]);
    }

    public function getRegions() :JsonResponse
    {
        return $this->responseData([
            'regions' => Region::all()
        ]);
    }

    public function getCities() :JsonResponse
    {
        return $this->responseData([
            'cities' => City::all()
        ]);
    }
}
