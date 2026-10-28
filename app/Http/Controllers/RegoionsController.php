<?php

namespace App\Http\Controllers;
use App\Domain\AppBase\Models\Car\CarsType;
use App\Domain\AppBase\Models\Location\Region;
use App\Domain\AppBase\Models\Location\City;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;

class RegoionsController extends Controller
{

    public function createRegionsAndCities()
    {
        $client = new Client(['verify' => false]);
        $url = "https://demo-apitawseel.naql.sa/api/Lookup/regions-list";
        $response = $client->post($url, [
            'body' => json_encode([
                "credential"=>[
                "companyName"=> "XXXXX",
                "password" => "XXXXX"
                ],
            ]),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ])->getBody();

        $regions = json_decode($response, true);        
        foreach($regions['data'] as $region){
            
            $regionIdx = Region::firstOrNew([
                'elm_id' => $region['id']
            ], [
                'title' => ['ar' => $region['nameAr'], 'en' => $region['nameEn']],
                'key' => $region['nameAr'],
                'country_id' => 1
            ]);
        
            if (!$regionIdx->exists) {
                $regionIdx->save();
            }

            $res = $client->post('https://demo-apitawseel.naql.sa/api/Lookup/cities-list', [
                'body' => json_encode([
                    "credential"=>[
                    "companyName"=> "XXXXX",
                    "password" => "XXXXX"
                    ],
                    "regionId" => $region['id'],
                ]),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ])->getBody();

            $cities = json_decode($res, true);
            foreach($cities['data'] as $city){
                $cityIdx = City::firstOrNew([
                    'elm_id' => $city['id']
                ], [
                    'title' => ['ar' => $city['nameAr'], 'en' => $city['nameEn']],
                    'key' => $city['nameAr'],
                    'elm_id' => $city['id'],
                    'region_id' => $regionIdx->id,
                    'country_id'=>1,
                ]);
            
                if (!$cityIdx->exists) {
                    $cityIdx->save();
                }
            }
            
        }
               
        return view('welcome',compact('regions'));
    }


    public function createCarsTyeps(){

        $client = new Client(['verify' => false]);
        $url = "https://demo-apitawseel.naql.sa/api/Lookup/car-types-list";
        $response = $client->post($url, [
            'body' => json_encode([
                "credential"=>[
                "companyName"=> "XXXXX",
                "password" => "XXXXX"
                ],
            ]),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ])->getBody();

        $carsTypes = json_decode($response, true);        
        foreach($carsTypes['data'] as $carType){
            
            $carTypeIdx = CarsType::firstOrNew([
                'elm_id' => $carType['id']
            ], [
                'title' => ['ar' => $carType['nameAr'], 'en' => $carType['nameEn']],
                'key' => $carType['nameAr'],
                'country_id' => 1
            ]);
        
            if (!$carTypeIdx->exists) {
                $carTypeIdx->save();
            }
        }
    
    }


    
}
