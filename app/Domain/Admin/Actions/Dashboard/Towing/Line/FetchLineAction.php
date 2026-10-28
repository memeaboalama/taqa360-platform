<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\Line;

use App\Domain\Towing\Repositories\TowingLineRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchLineAction
{
    protected TowingLineRepository $towingLineRepository;


    public function __construct(TowingLineRepository $towingLineRepository)
    {
        $this->towingLineRepository = $towingLineRepository;
    }


    public function handle(): Collection
    {
        return $this->towingLineRepository->all();
    }
}
