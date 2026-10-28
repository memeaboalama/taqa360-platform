<?php

namespace App\Domain\AppBase\Actions\Promocode;

use App\Domain\AppBase\Repositories\PromocodeRepository;
use App\Support\Enum\StatusEnum;

class EnablePromocodeAction
{
    protected PromocodeRepository $promocodeRepository;


    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->promocodeRepository = $promocodeRepository;
    }


    public function handle(int $id,$expired_at): bool
    {
        return $this->promocodeRepository->findByCols(['id' =>$id])->update(['status' => StatusEnum::ACTIVE->value,'expired_at'=>$expired_at]);
    }
}
