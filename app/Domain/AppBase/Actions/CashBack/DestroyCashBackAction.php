<?php

namespace App\Domain\AppBase\Actions\CashBack;

use App\Domain\AppBase\Repositories\CashBackRepository;
use App\Support\Enum\StatusEnum;

class DestroyCashBackAction
{
    protected CashBackRepository $cashBackRepository;


    public function __construct(CashBackRepository $cashBackRepository)
    {
        $this->cashBackRepository = $cashBackRepository;
    }


    public function handle(int $id): bool
    {
        return $this->cashBackRepository->findByCols(['id' =>$id])->update(['status' => StatusEnum::INACTIVE->value]);
    }
}
