<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Service;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;

class FetchSingleServiceAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(int $id, int $merchant_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->serviceRepository->findByCols(['id' => $id, 'merchant_id' => $merchant_id]);
    }

    public function getById(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->serviceRepository->findByCols(['id' => $id]);
    }

}

