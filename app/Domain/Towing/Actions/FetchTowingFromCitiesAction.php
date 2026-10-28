<?php

namespace App\Domain\Towing\Actions;

use App\Domain\Provider\Repositories\ProviderMainServiceRepository;
use App\Support\Enum\StatusEnum;
use Domain\Towing\Repositories\TowingRepository;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchTowingFromCitiesAction
{


    private TowingRepository $towingRepository;

    public function __construct(TowingRepository $towingRepository)
    {
        $this->towingRepository = $towingRepository;
    }

    /**
     * @param array $data
     * @return CursorPaginator|Collection
     */
    public function handle(array $data): CursorPaginator|Collection
    {
        $cols = ['id','title'];
        $relations = [];
        $conditions = ['can_pickup' => StatusEnum::ACTIVE];

        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->towingRepository->cursorPaginate(cols: $cols , relations: $relations,condition: $conditions, paginate: $per_page);
        }
        return $this->towingRepository->all(cols: $cols, relations: $relations, condition: $conditions);
    }
}
