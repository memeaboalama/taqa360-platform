<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Marketplace;

use App\Domain\MarketPlace\Repositories\Category\CategoryRepository;

class DestroyMarketplaceCategoryAction
{
    protected CategoryRepository $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }


    public function handle(int $id): bool
    {
        return $this->categoryRepository->destroy($id);
    }
}
