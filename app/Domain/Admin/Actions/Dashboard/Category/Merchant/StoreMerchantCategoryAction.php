<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class StoreMerchantCategoryAction
{
    protected MerchantCategoryRepository $merchantCategoryRepository;


    public function __construct(MerchantCategoryRepository $merchantCategoryRepository)
    {
        $this->merchantCategoryRepository = $merchantCategoryRepository;
    }

    public function handle(array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->merchantCategoryRepository->store($data);
    }
}
