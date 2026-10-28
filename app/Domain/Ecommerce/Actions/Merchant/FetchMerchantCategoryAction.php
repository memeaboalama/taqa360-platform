<?php

namespace App\Domain\Ecommerce\Actions\Merchant;

use App\Domain\MarketPlace\Repositories\Catalog\SubcategoryRepository;

class FetchMerchantCategoryAction
{
    private SubcategoryRepository $categoryRepository;

    public function __construct(SubcategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(int $merchant_id)
    {
        $subCategories = $this->categoryRepository->getUsedByMerchant($merchant_id);
        return $subCategories;
    }
}
