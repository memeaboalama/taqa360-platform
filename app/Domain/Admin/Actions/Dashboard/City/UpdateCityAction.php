<?php

namespace App\Domain\Admin\Actions\Dashboard\City;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Region;
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
        $city = City::query()->find($id);
        if (!$city) {
            return null;
        }

        $region = Region::query()->findOrFail($data['region_id']);
        $data['country_id'] = $region->country_id;
        $countryChanged = (int) $city->country_id !== (int) $data['country_id'];

        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        $updatedCity = $this->cityRepository->update($id, $data);

        if ($countryChanged) {
            $city->distracts()->update(['country_id' => $data['country_id']]);
        }

        return $updatedCity;
    }

}

