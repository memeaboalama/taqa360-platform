<?php

namespace App\Domain\AppBase\Actions\CashBack;

use App\Domain\AppBase\Repositories\CashBackRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchCashBackAction
{
    protected CashBackRepository $cashBackRepository;


    public function __construct(CashBackRepository $cashBackRepository)
    {
        $this->cashBackRepository = $cashBackRepository;
    }

    public function handle(): Collection
    {
        return $this->cashBackRepository->all();
    }
}
