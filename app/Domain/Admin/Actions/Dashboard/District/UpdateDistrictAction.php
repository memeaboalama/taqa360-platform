<?php

namespace App\Domain\Admin\Actions\Dashboard\District;

use App\Domain\AppBase\Models\Location\City;
use App\Domain\AppBase\Repositories\DistractRepository;

class UpdateDistrictAction
{
    protected DistractRepository $distractRepository;


    public function __construct(DistractRepository $distractRepository)
    {
        $this->distractRepository = $distractRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['country_id'] = City::query()->findOrFail($data['city_id'])->country_id;
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        return $this->distractRepository->update($id, $data);
    }

}

