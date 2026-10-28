<?php

namespace App\Domain\Towing\Actions;

use App\Domain\Provider\Repositories\ProviderMainServiceRepository;
use App\Support\Enum\StatusEnum;
use Domain\Towing\Repositories\TowingRepository;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FetchTowingFromCityAction
{


    private TowingRepository $towingRepository;

    public function __construct(TowingRepository $towingRepository)
    {
        $this->towingRepository = $towingRepository;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function handle(array $data): Model
    {
        $cols = ['id','title'];

        $conditions = ['can_pickup' => StatusEnum::ACTIVE];

        return $this->towingRepository->find(cols: $cols, conditions: $conditions);
    }
}
