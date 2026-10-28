<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\Line;

use App\Domain\Towing\Repositories\TowingLineRepository;

class DestroyLineAction
{
    protected TowingLineRepository $towingLineRepository;


    public function __construct(TowingLineRepository $towingLineRepository)
    {
        $this->towingLineRepository = $towingLineRepository;
    }


    public function handle(int $id): bool
    {
        return $this->towingLineRepository->destroy($id);
    }
}
