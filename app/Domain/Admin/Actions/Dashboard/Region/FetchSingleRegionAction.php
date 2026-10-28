<?php

namespace App\Domain\Admin\Actions\Dashboard\Region;

use App\Domain\Admin\Repositories\NationalityRepository;
use App\Domain\AppBase\Repositories\RegionRepository;

class FetchSingleRegionAction
{
    protected RegionRepository $regionRepository;


    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }


    public function handle($id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->regionRepository->findByCols(['id' => $id]);
    }

}

