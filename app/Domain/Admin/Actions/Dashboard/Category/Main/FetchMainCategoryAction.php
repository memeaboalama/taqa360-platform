<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Main;

use App\Domain\AppBase\Repositories\MainCategoryRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class FetchMainCategoryAction
{
    protected MainCategoryRepository $mainCategoryRepository;


    public function __construct(MainCategoryRepository $mainCategoryRepository)
    {
        $this->mainCategoryRepository = $mainCategoryRepository;
    }

    public function handle($except = false): Collection
    {
        if ($except) {return $this->mainCategoryRepository->all(cols: ['id', 'title'], condition: ["status" => StatusEnum::ACTIVE->value,['id','!=',3]]);}
        return $this->mainCategoryRepository->all(cols: ['id', 'title'], condition: ["status" => StatusEnum::ACTIVE->value]);

    }
}
