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



    public function handle(int $id, ?int $merchant_id = null): \Illuminate\Database\Eloquent\Model|null
    {
        $conditions = ['id' => $id];
        if ($merchant_id !== null) {
            $conditions['merchant_id'] = $merchant_id;
        }

        return $this->modelR->findByCols($conditions);
    }
}
