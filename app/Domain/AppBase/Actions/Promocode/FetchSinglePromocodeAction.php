<?php

namespace App\Domain\AppBase\Actions\Promocode;

use App\Domain\AppBase\Repositories\PromocodeRepository;

class FetchSinglePromocodeAction
{
    protected PromocodeRepository $promocodeRepository;


    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->promocodeRepository = $promocodeRepository;
    }
    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->promocodeRepository->findByCols(['id' => $id]);
    }
}
