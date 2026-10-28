<?php

namespace App\Domain\Provider\Actions\Dashboard\Order;

use App\Domain\Provider\Repositories\ProviderOrderRepository;

class FetchOrdersAction
{
    protected ProviderOrderRepository $providerOrderRepository;


    public function __construct(ProviderOrderRepository $providerOrderRepository)
    {
        $this->providerOrderRepository = $providerOrderRepository;
    }


    public function handle(int $company_id): \Illuminate\Database\Eloquent\Model|null
    {

        return $this->providerOrderRepository->findByCols(['company_id' => $company_id]);
    }

}
