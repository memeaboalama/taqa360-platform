<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class FetchSingleMerchantCategoryAction
{
    protected MerchantCategoryRepository $categoryRepository;


    public function __construct(MerchantCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function handle(int $id, int $merchant_id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->categoryRepository->findByCols(['merchant_id' => $merchant_id, 'id' => $id]);
    }
}
