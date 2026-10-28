<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Main;

use App\Domain\AppBase\Repositories\MainCategoryRepository;
use App\Domain\MarketPlace\Repositories\Category\CategoryRepository;

class UpdateMainCategoryAction
{
    protected MainCategoryRepository $mainCategoryRepository;


    public function __construct(MainCategoryRepository $mainCategoryRepository)
    {
        $this->mainCategoryRepository = $mainCategoryRepository;
    }

    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->mainCategoryRepository->update($id, $data);
    }
}
