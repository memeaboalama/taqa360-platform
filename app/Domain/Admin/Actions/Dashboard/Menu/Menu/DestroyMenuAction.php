<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Menu;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;

class DestroyMenuAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(int $id): bool
    {
        return $this->serviceRepository->forceDelete($id);
    }
}
