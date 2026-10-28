<?php

namespace App\Domain\Admin\Actions\Dashboard\City;

use App\Domain\Admin\Repositories\NationalityRepository;
use App\Domain\AppBase\Repositories\CityRepository;

class FetchSingleCityAction
{
    protected CityRepository $cityRepository;


    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }


    public function handle($id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->cityRepository->findByCols(['id' => $id]);
    }

}

