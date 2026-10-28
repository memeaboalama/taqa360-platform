<?php

namespace App\Domain\Admin\Actions\Dashboard\District;

use App\Domain\AppBase\Repositories\DistractRepository;

class FetchSingleDistrictAction
{
    protected DistractRepository $distractRepository;


    public function __construct(DistractRepository $distractRepository)
    {
        $this->distractRepository = $distractRepository;
    }


    public function handle($id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->distractRepository->findByCols(['id' => $id]);
    }

}

