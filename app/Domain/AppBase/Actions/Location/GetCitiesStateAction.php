<?php

namespace App\Domain\AppBase\Actions\Location;

use App\Support\Services\Map\GoogleMapService;


class GetCitiesStateAction
{
    protected $googleMapsService;

    public function __construct(GoogleMapService $googleMapsService)
    {
        $this->googleMapsService = $googleMapsService;
    }


    public function handle($latitude, $longitude): array|bool
    {

        $locationData = $this->googleMapsService->getCityAndState($latitude, $longitude);
        if (!$locationData) return false;

        $city = $locationData['city'];
        $city_short = $locationData['city_short'];
        $state = $locationData['state'];
        $state_short = $locationData['state_short'];

        return [
            'city' => $city,
            'state' => $state,
            'city_short' => $city_short,
            'state_short' => $state_short
        ];
    }
}
