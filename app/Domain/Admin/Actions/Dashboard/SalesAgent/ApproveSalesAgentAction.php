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
        return $this->salesAgentRepository->update($id, ['is_complete' => 1]);
    }
}
