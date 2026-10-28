<?php

namespace App\Domain\Admin\Actions\Dashboard\Region;

use App\Domain\AppBase\Repositories\RegionRepository;

class FetchRegionAction
{
    protected RegionRepository $regionRepository;


    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }


    public function handle(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->regionRepository->all();
    }

}

