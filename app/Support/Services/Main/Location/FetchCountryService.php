<?php

namespace App\Support\Services\Main\Location;

use App\Domain\Otp\Models\Country;
use App\Repositories\Main\CountryRepository;
use Illuminate\Support\Collection;

class FetchCountryService
{

    private CountryRepository $countryRepository;
    private $countries;

    /**
     * @param CountryRepository $countryRepository
     */
    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function handle(Collection $data): static
    {
        $this->countries = $this->countryRepository->query()->ownBuilder(columns: ['id','title'])->activeOnly()->fetch($data->get('paginated'));

        return $this;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->countries;
    }
}
