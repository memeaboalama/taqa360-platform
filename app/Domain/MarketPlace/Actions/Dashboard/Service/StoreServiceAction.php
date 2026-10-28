<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Service;
use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;

class StoreServiceAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $service = $this->serviceRepository->store($data);
//        if (isset($data['merchant_category_ids']))
//            $service->categories()->syncWithPivotValues($data['merchant_category_ids'], ['merchant_id' => auth()->guard('merchant')->user()->id]);
        return $service;
    }

}

