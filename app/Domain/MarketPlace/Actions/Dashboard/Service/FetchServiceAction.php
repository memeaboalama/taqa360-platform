<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Service;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchServiceAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->serviceRepository->getByMerchantId($merchant_id);
    }


    public function getByMerchant(int $merchant_id): Collection
    {
        return $this->serviceRepository->all(cols:['id', 'title', 'status', 'updated'], relations: ['categories', 'categories.items'], condition:["merchant_id"=>$merchant_id]);
    }

}

