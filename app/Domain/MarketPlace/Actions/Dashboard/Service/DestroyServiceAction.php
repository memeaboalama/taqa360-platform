<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Service;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;
use App\Support\Enum\ActiveEnum;

class DestroyServiceAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(int $id): bool
    {
        $menu = $this->serviceRepository->update($id, ['status' => ActiveEnum::INACTIVE, 'updated' => 'in-review']);
        return $menu->destroy($id);
    }
}
