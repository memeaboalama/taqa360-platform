<?php

namespace App\Domain\AppBase\Actions\CashBack;

use App\Domain\AppBase\Repositories\CashBackRepository;

class FetchSingleCashBackAction
{
    protected CashBackRepository $cashBackRepository;


    public function __construct(CashBackRepository $cashBackRepository)
    {
        $this->cashBackRepository = $cashBackRepository;
    }
    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->cashBackRepository->findByCols(['id' => $id]);
    }
}
