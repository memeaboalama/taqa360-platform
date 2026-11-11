<?php

namespace App\Domain\Admin\Actions\Dashboard\SalesAgent;

use App\Domain\SalesAgent\Repositories\SalesAgentRepository;

class ApproveSalesAgentAction
{
    protected SalesAgentRepository $salesAgentRepository;

    public function __construct(SalesAgentRepository $salesAgentRepository)
    {
        $this->salesAgentRepository = $salesAgentRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        $salesAgent = $this->salesAgentRepository->findByCols(['id' => $id]);

        if (!$salesAgent) {
            return null;
        }

        $salesAgent->update(['is_complete' => 1]);

        return $salesAgent;
    }
}
