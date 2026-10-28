<?php

namespace App\Domain\Admin\Actions\Dashboard\City;

use App\Domain\AppBase\Repositories\CityRepository;

class FetchCityAction
{
    protected CityRepository $cityRepository;


    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }


    public function handle(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->cityRepository->all();
    }

}

