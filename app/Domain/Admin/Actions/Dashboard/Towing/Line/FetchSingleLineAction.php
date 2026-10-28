<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\Line;

use App\Domain\Towing\Repositories\TowingLineRepository;

class FetchSingleLineAction
{
    protected TowingLineRepository $towingLineRepository;


    public function __construct(TowingLineRepository $towingLineRepository)
    {
        $this->towingLineRepository = $towingLineRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->towingLineRepository->findByCols(['id' => $id]);
    }
}
