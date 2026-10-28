<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;
use Illuminate\Database\Eloquent\Collection;

class FetchMerchantCategoryAction
{
    protected MerchantCategoryRepository $merchantCategoryRepository;


    public function __construct(MerchantCategoryRepository $merchantCategoryRepository)
    {
        $this->merchantCategoryRepository = $merchantCategoryRepository;
    }

    public function handle(): Collection
    {
        return $this->merchantCategoryRepository->all();
    }
}
