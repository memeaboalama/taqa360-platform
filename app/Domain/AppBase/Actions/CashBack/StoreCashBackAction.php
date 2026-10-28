<?php

namespace App\Domain\AppBase\Actions\CashBack;

use App\Domain\AppBase\Repositories\CashBackRepository;

class StoreCashBackAction
{
    protected CashBackRepository $cashBackRepository;


    public function __construct(CashBackRepository $cashBackRepository)
    {
        $this->cashBackRepository = $cashBackRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->cashBackRepository->store($data);
    }
}
