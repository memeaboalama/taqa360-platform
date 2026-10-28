<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class FetchMerchantCategoryAction
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
