<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Main;

use App\Domain\AppBase\Repositories\MainCategoryRepository;

class DestroyMainCategoryAction
{
    protected MainCategoryRepository $mainCategoryRepository;


    public function __construct(MainCategoryRepository $mainCategoryRepository)
    {
        $this->mainCategoryRepository = $mainCategoryRepository;
    }


    public function handle(int $id): bool
    {
        return $this->mainCategoryRepository->destroy($id);
    }
}
