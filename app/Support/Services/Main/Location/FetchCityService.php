<?php

namespace App\Support\Services\Main\Location;

use App\Domain\Otp\Models\Country;
use App\Repositories\Main\CityRepository;
use App\Repositories\Main\CountryRepository;
use Illuminate\Support\Collection;

class FetchCityService
{

    private CityRepository $cityRepository;
    private $cities;

    /**
     * @param CountryRepository cityRepository
     */
    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    public function handle(Collection $data): static
    {
        $with_relation = $data->get('with') != [] ? collect($data->get('with'))->map(fn($relation)=> "$relation:id,title")->toArray() : [];

        $this->cities = $this->cityRepository
            ->query()
            ->ownBuilder(columns: ['id','title','country_id','status'],relations: $with_relation)
            ->activeOnly()
            ->findByCountry($data->get('country_id'))
            ->fetch($data->get('paginated'));

        return $this;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        return $this->cities;
    }
}
