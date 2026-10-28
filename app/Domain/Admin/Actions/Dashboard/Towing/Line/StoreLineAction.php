<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\Line;

use App\Domain\Towing\Repositories\TowingLineRepository;

class StoreLineAction
{
    protected TowingLineRepository $towingLineRepository;


    public function __construct(TowingLineRepository $towingLineRepository)
    {
        $this->towingLineRepository = $towingLineRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->towingLineRepository->store($data);
    }
}
