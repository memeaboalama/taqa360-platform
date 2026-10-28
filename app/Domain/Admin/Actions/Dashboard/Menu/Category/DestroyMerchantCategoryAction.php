<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class DestroyMerchantCategoryAction
{
    protected MerchantCategoryRepository $categoryRepository;


    public function __construct(MerchantCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function handle(int $id): bool
    {
        return $this->categoryRepository->destroy($id);
    }
}
