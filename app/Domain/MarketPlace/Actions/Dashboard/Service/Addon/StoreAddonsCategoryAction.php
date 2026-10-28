<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Service\Addon;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;

class StoreAddonsCategoryAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $service =  $this->serviceRepository->findByCols(['id' => $data['service_id']]);
        $merchantId = auth()->guard('merchant')->user()->id;
        $addon_category = $service->addon_categories()->create([
            'merchant_id' => $merchantId,
            'title' => $data['title'],
            'status' => $data['status'],
        ]);
        foreach ($data['addon_title'] as $key=> $value){
            $addon_category->addons()->create([
                'merchant_id' => $merchantId,
                'merchant_service_id' => $data['service_id'],
                'title' => $value,
                'status' => $data['addon_status'][$key],
                'price' => $data['addon_price'][$key],
                'working_minutes' => $data['addon_working_minutes'][$key],
            ]);
        }

        return $service;
    }
}
