<?php

namespace App\Domain\Admin\Actions\Dashboard\Country;

use App\Domain\AppBase\Repositories\CountryRepository;

class UpdateCountryAction
{
    protected CountryRepository $countryRepository;


    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->countryRepository->update($id, $data);
    }

}