<?php

namespace App\Domain\Admin\Actions\Dashboard\Region;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Models\Location\Distract;
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
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        $region = $this->regionRepository->update($id, $data);

        $cityIds = City::query()->where('region_id', $id)->pluck('id');
        if ($cityIds->isNotEmpty()) {
            City::query()->whereIn('id', $cityIds)->update(['country_id' => $data['country_id']]);
            Distract::query()->whereIn('city_id', $cityIds)->update(['country_id' => $data['country_id']]);
        }

        return $region;
    }

}

