<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\Subcategory;

use App\Domain\MarketPlace\Repositories\Catalog\SubcategoryRepository;

class FetchSubcategoryAction
{
    protected SubcategoryRepository $subcategoryRepository;

    public function __construct(SubcategoryRepository $subcategoryRepository)
    {
        $this->subcategoryRepository = $subcategoryRepository;
    }


    public function handle(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->subcategoryRepository->all(['*']);
    }
}
