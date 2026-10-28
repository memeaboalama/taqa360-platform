<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantCategoryRepository;

class FetchSingleItemAction
{


    public function __construct(protected ItemRepository $modelR)
    {
        $this->modelR = $modelR;
    }



    public function handle(int $id, int $merchant_id = null): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->modelR->findByCols(['id' => $id]);
    }
}
