<?php

namespace App\Domain\AppBase\Actions\Car;

use App\Domain\AppBase\Repositories\CarTypeRepository;

class UpdateCarTypeAction
{
    protected CarTypeRepository $carTypeRepository;

    public function __construct(CarTypeRepository $carTypeRepository)
    {
        $this->carTypeRepository = $carTypeRepository;
    }

    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['key'] = filled($data['key'] ?? null) ? $data['key'] : ($data['title']['ar'] ?? null);
        return $this->carTypeRepository->update($id, $data);
    }
}
