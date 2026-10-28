<?php

namespace App\Domain\AppBase\Actions\Car;

use App\Domain\AppBase\Repositories\CarTypeRepository;

class FetchSingleCarTypeAction
{
    protected CarTypeRepository $carTypeRepository;

    public function __construct(CarTypeRepository $carTypeRepository)
    {
        $this->carTypeRepository = $carTypeRepository;
    }

    public function handle(int $id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->carTypeRepository->findByCols(['id' => $id]);
    }
}
