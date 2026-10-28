<?php

namespace App\Domain\AppBase\Actions\Location;

use App\Domain\AppBase\Repositories\DistractRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class GetDistrictsAction
{
    private DistractRepository $distractRepository;

    public function __construct(DistractRepository $distractRepository)
    {
        $this->distractRepository = $distractRepository;
    }

    /**
     * @param int|null $city_id
     */
    public function handle(int $city_id = null): Collection|array
    {
        if ($city_id) {
            return $this->distractRepository->getByCityId($city_id);
        }

        return $this->distractRepository->all(cols: ['id', 'title'], relations: [], condition: ['status' => StatusEnum::ACTIVE]);

    }
}
