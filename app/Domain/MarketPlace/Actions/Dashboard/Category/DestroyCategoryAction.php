<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class DestroyCategoryAction
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

