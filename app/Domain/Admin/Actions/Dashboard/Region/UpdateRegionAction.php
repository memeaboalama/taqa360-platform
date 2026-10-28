<?php

namespace App\Domain\Admin\Actions\Dashboard\Region;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Repositories\RegionRepository;

class UpdateRegionAction
{
    protected RegionRepository $regionRepository;


    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $city_id = $data['city_id'] ?? null;
        unset($data['city_id']);
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        $region = $this->regionRepository->update($id, $data);

        City::where('region_id', $id)->update(['region_id' => null]);

        if ($city_id) {
            City::where('id', $city_id)->update(['region_id' => $id]);
        }

        return $region;
    }

}

