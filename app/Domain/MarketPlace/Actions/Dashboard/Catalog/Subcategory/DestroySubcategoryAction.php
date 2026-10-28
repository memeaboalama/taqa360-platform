<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory;

use App\Domain\MarketPlace\Repositories\Catalog\SubcategoryRepository;

class DestroySubcategoryAction
{
    protected SubcategoryRepository $subcategoryRepository;


    public function __construct(SubcategoryRepository $subcategoryRepository)
    {
        $this->subcategoryRepository = $subcategoryRepository;
    }

    public function handle(int $id): bool
    {
        return $this->subcategoryRepository->destroy($id);
    }

}

