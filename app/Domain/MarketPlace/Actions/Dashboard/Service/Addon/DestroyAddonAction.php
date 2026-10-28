<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Service\Addon;

use App\Domain\MarketPlace\Repositories\Service\Addon\MerchantAddonCategoryRepository;
use App\Domain\MarketPlace\Repositories\Service\MerchantServiceRepository;

class DestroyAddonAction
{
    protected MerchantAddonCategoryRepository $merchantAddonCategoryRepository;


    public function __construct(MerchantAddonCategoryRepository $merchantAddonCategoryRepository)
    {
        $this->merchantAddonCategoryRepository = $merchantAddonCategoryRepository;
    }


    public function handle(int $id): bool
    {
        return $this->merchantAddonCategoryRepository->destroy($id);
    }
}
