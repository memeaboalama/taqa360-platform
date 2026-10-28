<?php

namespace App\Domain\Admin\Actions\Dashboard\District;

use App\Domain\AppBase\Repositories\DistractRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class FetchDistrictAction
{
    protected DistractRepository $distractRepository;


    public function __construct(DistractRepository $distractRepository)
    {
        $this->distractRepository = $distractRepository;
    }

    public function all(): Collection
    {
        return $this->distractRepository->all(cols: ['id','title'], condition: ['status'=>StatusEnum::ACTIVE]);
    }


    public function handle(): \Illuminate\Database\Eloquent\Builder
    {
        return $this->distractRepository->all();
    }

}

