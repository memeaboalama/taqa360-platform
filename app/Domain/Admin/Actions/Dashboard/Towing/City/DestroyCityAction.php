<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\City;

use App\Domain\Towing\Repositories\TowingCityRepository;

class DestroyCityAction
{
    protected TowingCityRepository $towingCityRepository;


    public function __construct(TowingCityRepository $towingCityRepository)
    {
        $this->towingCityRepository = $towingCityRepository;
    }


    public function handle(int $id): bool
    {
        return $this->towingCityRepository->destroy($id);
    }
}
