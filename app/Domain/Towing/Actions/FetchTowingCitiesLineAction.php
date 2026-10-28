<?php

namespace App\Domain\Towing\Actions;

use App\Domain\Provider\Repositories\ProviderMainServiceRepository;
use App\Domain\Towing\Repositories\TowingLineRepository;
use App\Support\Enum\StatusEnum;
use Domain\Towing\Repositories\TowingRepository;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchTowingCitiesLineAction
{


    private TowingLineRepository $towingRepository;

    public function __construct(TowingLineRepository $towingRepository)
    {
        $this->towingRepository = $towingRepository;
    }

    /**
     * @param array $data
     * @return CursorPaginator|Collection
     */
    public function handle(array $data): CursorPaginator|Collection
    {
        $cols = ['id','price','from_towing_city_id','to_towing_city_id','towing_type_id'];
        $relations = ['to_city:id,title'];
        $conditions = ['from_towing_city_id' => $data['from_city_id'],'towing_type_id' => $data['towing_type_id']];

        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->towingRepository->cursorPaginate(cols: $cols , relations: $relations,condition: $conditions, paginate: $per_page);
        }
        return $this->towingRepository->all(cols: $cols, relations: $relations, condition: $conditions);
    }
}
