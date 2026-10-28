<?php

namespace App\Domain\Admin\Actions\Dashboard\SalesAgent;

use App\Domain\SalesAgent\Repositories\SalesAgentRepository;


class DestroySalesAgentAction
{
    protected SalesAgentRepository $salesAgentRepository;


    public function __construct(SalesAgentRepository $salesAgentRepository)
    {
        $this->salesAgentRepository = $salesAgentRepository;
    }

    public function handle(int $id): bool
    {
        return $this->salesAgentRepository->findByCols(['id' => $id])->delete();
    }
}
