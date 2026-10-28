<?php

namespace App\Domain\Admin\Actions\Dashboard\District;

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
        $data['key'] = isset($data['title']['ar']) ? $data['title']['ar'] : null;
        return $this->distractRepository->update($id, $data);
    }

}

