<?php

namespace App\Domain\Admin\Actions\Dashboard\Region;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Repositories\RegionRepository;

class StoreRegionAction
{
    protected RegionRepository $regionRepository;


    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $city_id = $data['city_id'] ?? null;
        unset($data['city_id']);
        $data['country_id'] = 1;
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        $region = $this->regionRepository->store($data);

        if ($region && $city_id) {
            City::where('id', $city_id)->update(['region_id' => $region->id]);
        }

        return $region;
    }

}

