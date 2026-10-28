<?php

namespace App\Domain\AppBase\Actions\Car;

use App\Domain\AppBase\Repositories\CarTypeRepository;

class DestroyCarTypeAction
{
    protected CarTypeRepository $carTypeRepository;

    public function __construct(CarTypeRepository $carTypeRepository)
    {
        $this->carTypeRepository = $carTypeRepository;
    }

    public function handle(int $id): bool
    {
        return $this->carTypeRepository->destroy($id);
    }
}
