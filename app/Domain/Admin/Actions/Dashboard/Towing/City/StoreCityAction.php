<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\City;

use App\Domain\Towing\Repositories\TowingCityRepository;
use MatanYadaev\EloquentSpatial\Objects\Point;

class StoreCityAction
{
    protected TowingCityRepository $towingCityRepository;


    public function __construct(TowingCityRepository $towingCityRepository)
    {
        $this->towingCityRepository = $towingCityRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['location'] = new Point(24.7254569,47.151498,4326);
        return $this->towingCityRepository->store($data);
    }
}
