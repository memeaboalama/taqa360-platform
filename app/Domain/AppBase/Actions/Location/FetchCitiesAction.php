<?php

namespace App\Domain\AppBase\Actions\Location;

use App\Domain\AppBase\Repositories\CityRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchCitiesAction
{
    private CityRepository $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = []): LengthAwarePaginator|Collection
    {
        $relations = isset($data['with']) ? [$data['with'] . ":id,title,city_id"] : [];

        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->cityRepository->paginate(cols: ['id','title'], relations: $relations, condition: ['status'=>StatusEnum::ACTIVE], paginate: $per_page);
        }
        return $this->cityRepository->all(cols: ['id','title'],relations:$relations, condition: ['status'=>StatusEnum::ACTIVE]);
    }
}
