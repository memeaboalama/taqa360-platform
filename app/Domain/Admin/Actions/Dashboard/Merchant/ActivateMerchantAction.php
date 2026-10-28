<?php

namespace App\Domain\Admin\Actions\Dashboard\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantRepository;

class ActivateMerchantAction
{
    protected MerchantRepository $merchantRepository;

    public function __construct(MerchantRepository $merchantRepository)
    {
        $this->merchantRepository = $merchantRepository;
    }

    public function activate(int $id)
    {
        return $this->merchantRepository->update($id, ['status'=>'active']);
    }

    public function deactivate(int $id)
    {
        return $this->merchantRepository->update($id, ['status'=>'inactive']);
    }
}
