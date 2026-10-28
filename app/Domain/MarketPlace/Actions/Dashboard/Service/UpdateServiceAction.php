<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Service;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;

class UpdateServiceAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(int $service_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        // $data['updated'] = 'in-review';
        return $this->serviceRepository->update($service_id, $data);
    }

    public function accept(int $service_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->serviceRepository->update($service_id, $data);
    }
}
