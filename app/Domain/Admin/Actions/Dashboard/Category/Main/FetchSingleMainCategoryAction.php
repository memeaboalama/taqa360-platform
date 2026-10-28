<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Main;

use App\Domain\AppBase\Repositories\MainCategoryRepository;
use App\Domain\MarketPlace\Repositories\Category\CategoryRepository;

class FetchSingleMainCategoryAction
{
    protected MainCategoryRepository $mainCategoryRepository;


    public function __construct(MainCategoryRepository $mainCategoryRepository)
    {
        $this->mainCategoryRepository = $mainCategoryRepository;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->mainCategoryRepository->findByCols(['id' => $id]);
    }
}
