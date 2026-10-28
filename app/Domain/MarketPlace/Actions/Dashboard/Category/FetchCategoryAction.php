<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class FetchCategoryAction
{
    protected MerchantCategoryRepository $categoryRepository;


    public function __construct(MerchantCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function handle(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->categoryRepository->getCategoriesByMerchantId($merchant_id);
    }

}

