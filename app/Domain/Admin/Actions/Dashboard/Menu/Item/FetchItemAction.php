<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;

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
