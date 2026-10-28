<?php

namespace App\Domain\AppBase\Actions\Location;

use App\Domain\AppBase\Repositories\RegionRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchRegionsAction
{
    private RegionRepository $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator|Collection
     */
    public function handle(array $data = []): LengthAwarePaginator|Collection
    {
        $relations = isset($data['with']) ? [$data['with'] . ":id,title,region_id"] : [];

        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->regionRepository->paginate(cols: ['id','title'], relations: $relations, condition: ['status'=>StatusEnum::ACTIVE], paginate: $per_page);
        }
        return $this->regionRepository->all(cols: ['id','title'],relations:$relations, condition: ['status'=>StatusEnum::ACTIVE]);
    }
}
