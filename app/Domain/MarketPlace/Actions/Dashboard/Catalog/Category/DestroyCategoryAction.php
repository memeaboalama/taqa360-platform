<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category;

use App\Domain\MarketPlace\Repositories\Catalog\CategoryRepository;

class DestroyCategoryAction
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

