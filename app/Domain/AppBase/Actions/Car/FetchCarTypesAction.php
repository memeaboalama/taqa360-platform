<?php

namespace App\Domain\AppBase\Actions\Car;

use App\Domain\AppBase\Repositories\CarTypeRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchCarTypesAction
{
    private CarTypeRepository $carTypeRepository;

    public function __construct(CarTypeRepository $carTypeRepository)
    {
        $this->carTypeRepository = $carTypeRepository;
    }

    /**
     * @return Collection
     */
    public function handle(bool $activeOnly = false): Collection
    {
        return $this->carTypeRepository->all(
            cols: ['id','title','elm_id'],
            condition: $activeOnly ? ['is_active' => 1] : []
        );
    }
}
