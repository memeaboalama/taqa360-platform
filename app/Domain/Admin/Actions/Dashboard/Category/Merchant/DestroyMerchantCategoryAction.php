<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class DestroyMerchantCategoryAction
{
    protected MerchantCategoryRepository $merchantCategoryRepository;


    public function __construct(MerchantCategoryRepository $merchantCategoryRepository)
    {
        $this->merchantCategoryRepository = $merchantCategoryRepository;
    }


    public function handle(int $id): bool
    {
        return $this->merchantCategoryRepository->destroy($id);
    }
}
