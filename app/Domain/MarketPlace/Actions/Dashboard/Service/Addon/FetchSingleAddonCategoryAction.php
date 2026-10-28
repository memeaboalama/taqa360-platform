<?php

namespace Domain\MarketPlace\Actions\Dashboard\Service\Addon;

use App\Domain\MarketPlace\Repositories\Service\Addon\MerchantAddonCategoryRepository;

class FetchSingleAddonCategoryAction
{
    protected MerchantAddonCategoryRepository $merchantAddonCategoryRepository;


    public function __construct(MerchantAddonCategoryRepository $merchantAddonCategoryRepository)
    {
        $this->merchantAddonCategoryRepository = $merchantAddonCategoryRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->merchantAddonCategoryRepository->findByCols(['id'=>$id]);
    }
}
