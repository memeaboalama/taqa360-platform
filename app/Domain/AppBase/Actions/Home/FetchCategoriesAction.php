<?php

namespace App\Domain\AppBase\Actions\Home;

use App\Domain\MarketPlace\Actions\Dashboard\Catalog\Category\FetchCategoryAction;
use Illuminate\Support\Facades\Cache;

class FetchCategoriesAction
{
    public function __construct(
        private FetchCategoryAction $fetchCategoryAction
    ) {}

    public function handle()
    {
        return Cache::remember('sidebar_categories_' . app()->getLocale(), 3600, function() {
            return $this->fetchCategoryAction->handle();
        });
    }
}
