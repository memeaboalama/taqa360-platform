<?php

namespace App\Domain\Admin\Actions\Dashboard\Country;

use App\Domain\AppBase\Repositories\CountryRepository;

class FetchSingleCountryAction
{
    protected CountryRepository $countryRepository;


    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }


    public function handle($id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->countryRepository->findByCols(['id' => $id]);
    }

}