<?php

namespace App\Domain\Admin\Actions\Dashboard\Country;

use App\Domain\AppBase\Repositories\CountryRepository;

class DestroyCountryAction
{
    protected CountryRepository $countryRepository;


    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }


    public function handle(int $id): bool
    {
        return $this->countryRepository->destroy($id);
    }

}