<?php

namespace App\Domain\Admin\Actions\Dashboard\City;

use App\Domain\AppBase\Repositories\CityRepository;

class UpdateCityAction
{
    protected CityRepository $cityRepository;


    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        return $this->cityRepository->update($id, $data);
    }

}

