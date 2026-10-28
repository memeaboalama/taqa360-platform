<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category;

use App\Domain\MarketPlace\Repositories\Catalog\CategoryRepository;

class FetchSingleCategoryAction
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

