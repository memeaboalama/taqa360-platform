<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Menu;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;

class StoreMenuAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        $service = $this->serviceRepository->store($data);
        return $service;
    }
}
