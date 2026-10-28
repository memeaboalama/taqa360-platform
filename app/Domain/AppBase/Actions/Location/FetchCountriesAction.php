<?php

namespace App\Domain\AppBase\Actions\Location;

use App\Domain\AppBase\Repositories\CityRepository;
use App\Domain\AppBase\Repositories\CountryRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchCountriesAction
{
    private CountryRepository $countryRepository;

    public function __construct(CountryRepository $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }


    /**
     * @return Collection
     */
    public function handle(): Collection
    {
        return $this->countryRepository->all(cols: ['id', 'title'], relations: ['regions'], condition: ['status' => StatusEnum::ACTIVE]);
    }
}
