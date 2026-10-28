<?php

namespace App\Domain\Provider\Actions\Dashboard\Order;

use App\Domain\Provider\Repositories\ProviderOrderRepository;

class FetchSingleOrderAction
{
    protected ProviderOrderRepository $providerOrderRepository;


    public function __construct(ProviderOrderRepository $providerOrderRepository)
    {
        $this->providerOrderRepository = $providerOrderRepository;
    }


    public function handle(int $id, int $company_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->providerOrderRepository->findByCols(['company_id' => $company_id, 'id' => $id]);
    }

    public function getByUserId(int $id, int $user_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->providerOrderRepository->findByCols(['user_id' => $user_id, 'id' => $id]);
    }
}
