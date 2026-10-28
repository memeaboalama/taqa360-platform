<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class UpdateMerchantCategoryAction
{
    protected MerchantCategoryRepository $categoryRepository;


    public function __construct(MerchantCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function handle(int $category_id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->categoryRepository->update($category_id, $data);
    }
}
