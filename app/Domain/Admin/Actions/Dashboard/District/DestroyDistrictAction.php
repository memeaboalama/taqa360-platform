<?php

namespace App\Domain\Admin\Actions\Dashboard\District;

use App\Domain\AppBase\Repositories\DistractRepository;

class DestroyDistrictAction
{
    protected DistractRepository $distractRepository;


    public function __construct(DistractRepository $distractRepository)
    {
        $this->distractRepository = $distractRepository;
    }


    public function handle(int $id): bool
    {
        return $this->distractRepository->destroy($id);
    }

}

