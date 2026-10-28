<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\City;

use App\Domain\Towing\Repositories\TowingCityRepository;

class UpdateCityAction
{
    protected TowingCityRepository $towingCityRepository;


    public function __construct(TowingCityRepository $towingCityRepository)
    {
        $this->towingCityRepository = $towingCityRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->towingCityRepository->update($id, $data);
    }
}
