<?php

namespace App\Domain\Admin\Actions\Dashboard\Package;

use App\Domain\MarketPlace\Repositories\Merchant\PackageRepository;

class StorePackageAction
{
    protected PackageRepository $packageRepository;


    public function __construct(PackageRepository $packageRepository)
    {
        $this->packageRepository = $packageRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $model= $this->packageRepository->store($data);
        
        foreach ($data['package_type_services'] as $option) 
        {
            $model->type_services()->attach($option['type_service_id'], ['commission' => $option['commission'], 'discount_rate' => $option['discount_rate'], 'desc' => $option['desc'], 'status' => $option['status']]);
        }
        return $model;
    }
}
