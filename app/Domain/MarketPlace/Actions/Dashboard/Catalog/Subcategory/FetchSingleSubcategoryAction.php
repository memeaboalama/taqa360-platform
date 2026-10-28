<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory;

use App\Domain\MarketPlace\Repositories\Catalog\SubcategoryRepository;

class FetchSingleSubcategoryAction
{
    protected SubcategoryRepository $subcategoryRepository;

    public function __construct(SubcategoryRepository $subcategoryRepository)
    {
        $this->subcategoryRepository = $subcategoryRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->subcategoryRepository->findByCols(['id' => $id]);
    }
}

