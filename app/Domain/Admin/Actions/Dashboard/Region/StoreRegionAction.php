<?php

namespace App\Domain\Admin\Actions\Dashboard\Region;

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
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        return $this->regionRepository->store($data);
    }

}

