<?php

namespace App\Domain\Admin\Actions\Dashboard\Region;

use App\Domain\AppBase\Repositories\RegionRepository;

class DestroyRegionAction
{
    protected RegionRepository $regionRepository;


    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }


    public function handle(int $id): bool
    {
        return $this->regionRepository->destroy($id);
    }

}

