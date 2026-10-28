<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\ChildCategory;

use App\Domain\MarketPlace\Repositories\Catalog\ChildCategoryRepository;

class FetchChildCategoryAction
{
    protected ChildCategoryRepository $childcategoryRepository;


    public function __construct(ChildCategoryRepository $childcategoryRepository)
    {
        $this->childcategoryRepository = $childcategoryRepository;
    }


    public function handle(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->childcategoryRepository->all(['*']);
    }
}
