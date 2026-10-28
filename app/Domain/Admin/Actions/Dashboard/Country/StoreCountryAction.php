<?php

namespace App\Domain\Admin\Actions\Dashboard\Country;

use App\Domain\AppBase\Repositories\CountryRepository;

class StoreCountryAction
{
    protected CountryRepository $countryRepository;


    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['currency_id'] = $data['currency_id'] ?? 1;
        $data['vat'] = $data['vat'] ?? 0;
        return $this->countryRepository->store($data);
    }

}