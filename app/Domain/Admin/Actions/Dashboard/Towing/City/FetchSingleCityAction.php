<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\City;

use App\Domain\Towing\Repositories\TowingCityRepository;

class FetchSingleCityAction
{
    protected TowingCityRepository $towingCityRepository;


    public function __construct(TowingCityRepository $towingCityRepository)
    {
        $this->towingCityRepository = $towingCityRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->towingCityRepository->findByCols(['id' => $id]);
    }
}
