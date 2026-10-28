<?php

namespace App\Domain\Admin\Actions\Dashboard\Category\Merchant;

use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class UpdateMerchantCategoryAction
{
    protected MerchantCategoryRepository $merchantCategoryRepository;


    public function __construct(MerchantCategoryRepository $merchantCategoryRepository)
    {
        $this->merchantCategoryRepository = $merchantCategoryRepository;
    }

    public function handle(int $id, array $data = []): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->merchantCategoryRepository->update($id, $data);
    }
}
