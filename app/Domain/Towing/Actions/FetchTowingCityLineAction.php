<?php

namespace App\Domain\Towing\Actions;

use App\Domain\Provider\Repositories\ProviderMainServiceRepository;
use App\Domain\Towing\Repositories\TowingLineRepository;
use App\Support\Enum\StatusEnum;
use Domain\Towing\Repositories\TowingRepository;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class FetchTowingCityLineAction
{


    private TowingLineRepository $towingRepository;

    public function __construct(TowingLineRepository $towingRepository)
    {
        $this->towingRepository = $towingRepository;
    }

    /**
     * @param int $destination_city_id
     * @param int $from_towing_city_id
     * @param int $towing_type_id
     * @return Model
     */
    public function handle(int $destination_city_id ,int $from_towing_city_id ,int $towing_type_id): ?Model
    {
        $cols = ['id','price','from_towing_city_id','to_towing_city_id','towing_type_id'];
        $conditions = ['from_towing_city_id' => $from_towing_city_id,'towing_type_id' => $towing_type_id,'to_towing_city_id' => $destination_city_id];

        return $this->towingRepository->find(cols: $cols, conditions: $conditions);
    }
}
