<?php

namespace App\Domain\Admin\Actions\Dashboard\Towing\Type;

use App\Domain\Towing\Repositories\TowingTypeRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchTypeAction
{
    protected TowingTypeRepository $towingTypeRepository;


    public function __construct(TowingTypeRepository $towingTypeRepository)
    {
        $this->towingTypeRepository = $towingTypeRepository;
    }


    public function handle(): Collection
    {
        return $this->towingTypeRepository->all();
    }
}
