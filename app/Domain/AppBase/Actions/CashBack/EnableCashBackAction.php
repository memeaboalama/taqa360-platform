<?php

namespace App\Domain\AppBase\Actions\CashBack;

use App\Domain\AppBase\Repositories\CashBackRepository;
use App\Support\Enum\StatusEnum;

class EnableCashBackAction
{
    protected CashBackRepository $cashbackRepository;


    public function __construct(CashBackRepository $cashbackRepository)
    {
        $this->cashbackRepository = $cashbackRepository;
    }


    public function handle(int $id,$expired_at): bool
    {
        return $this->cashbackRepository->findByCols(['id' =>$id])->update(['status' => StatusEnum::ACTIVE->value,'expired_at'=>$expired_at]);
    }
}
