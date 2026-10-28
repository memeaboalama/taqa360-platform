<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Marketplace;

use App\Domain\MarketPlace\Repositories\Category\CategoryRepository;

class FetchSingleMarketplaceCategoryAction
{
    protected CategoryRepository $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->categoryRepository->findByCols(['id' => $id]);
    }
}
