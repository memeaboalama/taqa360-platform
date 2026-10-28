<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class FetchItemAction
{


    public function __construct(protected ItemRepository $modelR)
    {
        $this->modelR = $modelR;
    }



    public function handle(int $merchant_id): \Illuminate\Database\Eloquent\Builder
    {
        return $this->modelR->getCategoriesByMerchantId($merchant_id);
    }
}
