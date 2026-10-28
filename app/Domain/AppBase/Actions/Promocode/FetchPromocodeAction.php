<?php

namespace App\Domain\AppBase\Actions\Promocode;

use App\Domain\AppBase\Repositories\PromocodeRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchPromocodeAction
{
    protected PromocodeRepository $promocodeRepository;


    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->promocodeRepository = $promocodeRepository;
    }

    public function handle(): Collection
    {
        return $this->promocodeRepository->all();
    }
}
