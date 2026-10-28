<?php

namespace App\Domain\Provider\Actions\Dashboard\Provider;

use Domain\Provider\Repositories\ProviderRepository;

class FetchSingleProviderAction
{
    protected ProviderRepository $providerRepository;


    public function __construct(ProviderRepository $providerRepository)
    {
        $this->providerRepository = $providerRepository;
    }


    public function handle(int $id, int $company_id = null): \Illuminate\Database\Eloquent\Model|null
    {
        $conditions = ['id' => $id];
        if ($company_id) {
            $conditions['company_id'] = $company_id;
        }
        return $this->providerRepository->findByCols($conditions);
    }

}

