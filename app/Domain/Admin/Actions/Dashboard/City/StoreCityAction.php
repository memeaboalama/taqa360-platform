<?php

namespace App\Domain\Admin\Actions\Dashboard\City;

use App\Domain\AppBase\Repositories\CityRepository;

class StoreCityAction
{
    protected CityRepository $cityRepository;


    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['country_id'] = 1;
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        return $this->cityRepository->store($data);
    }

}

