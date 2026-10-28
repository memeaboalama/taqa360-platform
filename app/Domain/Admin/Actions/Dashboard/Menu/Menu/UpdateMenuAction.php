<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Menu;

use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;

class UpdateMenuAction
{
    protected MerchantServiceRepository $serviceRepository;


    public function __construct(MerchantServiceRepository $serviceRepository)
    {
        $this->serviceRepository = $serviceRepository;
    }


    public function handle(int $menu_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->serviceRepository->update($menu_id, $data);
    }

    public function accept(int $menu_id): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->serviceRepository->update($menu_id, ['updated' => 'accepted']);
    }
}
