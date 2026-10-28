<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Marketplace;

use App\Domain\MarketPlace\Repositories\Category\CategoryRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Collection;

class FetchMarketplaceCategoryAction
{
    protected CategoryRepository $categoryRepository;


    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function handle(): Collection
    {
        return $this->categoryRepository->all(cols:['id', 'title','sort'],condition:["status"=>StatusEnum::ACTIVE->value]);
    }
}
