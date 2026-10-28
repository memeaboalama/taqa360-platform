<?php

namespace App\Domain\Admin\Actions\Dashboard\District;

use App\Domain\AppBase\Repositories\DistractRepository;

class StoreDistrictAction
{
    protected DistractRepository $distractRepository;


    public function __construct(DistractRepository $distractRepository)
    {
        $this->distractRepository = $distractRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $data['country_id'] = 1;
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        return $this->distractRepository->store($data);
    }

}

