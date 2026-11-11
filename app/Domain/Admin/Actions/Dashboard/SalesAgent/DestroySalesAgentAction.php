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
        $salesAgent = $this->salesAgentRepository->findByCols(['id' => $id]);

        return $salesAgent ? (bool) $salesAgent->delete() : false;
    }
}
