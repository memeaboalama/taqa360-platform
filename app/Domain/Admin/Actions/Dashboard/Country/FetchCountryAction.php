<?php

namespace App\Domain\Admin\Actions\Dashboard\Country;

use App\Domain\AppBase\Repositories\CountryRepository;

class FetchCountryAction
{
    protected CountryRepository $countryRepository;


    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }


    public function handle(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->countryRepository->all();
    }

}