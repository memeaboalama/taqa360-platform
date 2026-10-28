<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\City;

use App\Domain\Towing\Repositories\TowingCityRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchCityAction
{
    protected TowingCityRepository $towingCityRepository;


    public function __construct(TowingCityRepository $towingCityRepository)
    {
        $this->towingCityRepository = $towingCityRepository;
    }


    public function handle(): Collection
    {
        return $this->towingCityRepository->all();
    }
}
