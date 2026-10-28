<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\Line;

use App\Domain\Towing\Repositories\TowingLineRepository;

class UpdateLineAction
{
    protected TowingLineRepository $towingLineRepository;


    public function __construct(TowingLineRepository $towingLineRepository)
    {
        $this->towingLineRepository = $towingLineRepository;
    }


    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->towingLineRepository->update($id, $data);
    }
}
