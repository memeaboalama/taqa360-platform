<?php

namespace App\Domain\Admin\Actions\Dashboard\SalesAgent;

use App\Domain\SalesAgent\Repositories\SalesAgentRepository;
use Illuminate\Support\Facades\Hash;
use MatanYadaev\EloquentSpatial\Objects\Point;

class UpdateSalesAgentAction
{
    protected SalesAgentRepository $salesAgentRepository;


    public function __construct(SalesAgentRepository $salesAgentRepository)
    {
        $this->salesAgentRepository = $salesAgentRepository;
    }

    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $salesAgent = $this->salesAgentRepository->findByCols(['id' => $id]);
        if (!$salesAgent) {
            return null;
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $salesAgent->update($data);

        return $salesAgent;
    }
}
