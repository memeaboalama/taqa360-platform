<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Catalog\ChildCategory;

use App\Domain\MarketPlace\Repositories\Catalog\ChildCategoryRepository;

class FetchSingleChildCategoryAction
{
    protected ChildCategoryRepository $childcategoryRepository;

    public function __construct(ChildCategoryRepository $childcategoryRepository)
    {
        $this->childcategoryRepository = $childcategoryRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->childcategoryRepository->findByCols(['id' => $id]);
    }
}

