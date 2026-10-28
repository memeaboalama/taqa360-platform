<?php

namespace App\Support\Services\Map;

use GuzzleHttp\Client;

class GoogleMapService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        // $this->apiKey = config('services.google_maps.api_key');
        $this->apiKey = 'AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0';
    }

    public function getCityAndState($latitude, $longitude): array|bool
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$latitude},{$longitude}&key={$this->apiKey}&political|locality|sublocality|administrative_area_level_1|administrative_area_level_2|administrative_area_level_3|administrative_area_level_4|administrative_area_level_5&language=ar&region=sa";

        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);
        $cityName = null;
        $cityName_short = null;
        $stateName = null;
        $stateName_short = null;
        $aar = [];

        if (empty($data['results'])) return false;

        $result = $data['results'][0];

        $components = $result['address_components'];

        foreach ($components as $component) {
            if (in_array('sublocality', $component['types'])) {
                $stateName = $component['long_name'];
                $stateName_short = $component['short_name'];
                break;
            }
            if (in_array('administrative_area_level_5', $component['types'])) {

                $stateName = $component['long_name'];
                $stateName_short = $component['short_name'];
                break;
            }
            if (in_array('administrative_area_level_4', $component['types'])) {

                $stateName = $component['long_name'];
                $stateName_short = $component['short_name'];
                break;
            }
            if (in_array('administrative_area_level_3', $component['types'])) {

                $stateName = $component['long_name'];
                $stateName_short = $component['short_name'];
                break;
            }
            if (in_array('locality', $component['types'])) {

                $stateName = $component['long_name'];
                $stateName_short = $component['short_name'];
                break;
            }
        }
        foreach ($components as $component) {

            if (in_array('locality', $component['types'])) {

                $cityName = $component['long_name'];
                $cityName_short = $component['short_name'];
                break;
            }
            if (in_array('administrative_area_level_1', $component['types'])) {

                $cityName = $component['long_name'];
                $cityName_short = $component['short_name'];
                break;
            }

            if (in_array('administrative_area_level_2', $component['types'])) {

                $cityName = $component['long_name'];
                $cityName_short = $component['short_name'];
                break;
            }


        }
        return [
            'city' => $cityName,
            'state' => $stateName,
            'city_short' => $cityName_short,
            'state_short' => $stateName_short
        ];
    }
}
