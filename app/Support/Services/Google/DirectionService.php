<?php

namespace App\Support\Services\Google;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Http;

class DirectionService
{
    private string $destination;
    private string $origin;

    private string $apiKey;
    private array $results;

    public function __construct(string $destination , string $origin)
    {
        $this->destination = $destination;
        $this->origin = $origin;
        // $this->apiKey = config('services.google_maps.api_key');
        $this->apiKey = 'AIzaSyCIJH6jbULC8MVaAX2LyxIQEBwWPjjeIF0';
    }
    public function send_request(): array
    {
        $url = "https://maps.googleapis.com/maps/api/directions/json?destination=$this->destination&origin=$this->origin&key=$this->apiKey";

        $result = Http::get($url)->body();

        $this->results =  json_decode($result,true);

        return $this->results;
    }

    public function filtered_results()
    {
        $this->send_request();
        return $this->results['routes'][0]['legs'][0] ?? [];
    }
}
