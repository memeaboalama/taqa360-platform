<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Category;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class UpdateCategoryAction
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

