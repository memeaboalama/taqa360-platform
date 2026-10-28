<?php

namespace App\Domain\AppBase\Actions\Car;

use App\Domain\AppBase\Repositories\CarTypeRepository;
use Illuminate\Database\Eloquent\Model;

class StoreCarTypeAction
{
    protected CarTypeRepository $carTypeRepository;

    public function __construct(CarTypeRepository $carTypeRepository)
    {
        $this->carTypeRepository = $carTypeRepository;
    }

    public function handle(array $data = []): ?Model
    {
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        return $this->carTypeRepository->store($data);
    }
}
