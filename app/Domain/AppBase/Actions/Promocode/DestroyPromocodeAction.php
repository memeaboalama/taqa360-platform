<?php

namespace App\Domain\AppBase\Actions\Promocode;

use App\Domain\AppBase\Repositories\PromocodeRepository;
use App\Support\Enum\StatusEnum;

class DestroyPromocodeAction
{
    protected PromocodeRepository $promocodeRepository;


    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->promocodeRepository = $promocodeRepository;
    }


    public function handle(int $id): bool
    {
        return $this->promocodeRepository->findByCols(['id' =>$id])->update(['status' => StatusEnum::INACTIVE->value]);
    }
}
