<?php

namespace App\Domain\Admin\Actions\Dashboard\City;

use App\Domain\AppBase\Repositories\CityRepository;

class DestroyCityAction
{
    protected CityRepository $cityRepository;


    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }


    public function handle(int $id): bool
    {
        return $this->cityRepository->destroy($id);
    }

}

