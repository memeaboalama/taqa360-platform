<?php

namespace App\Domain\MarketPlace\Actions\Dashboard\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Domain\MarketPlace\Repositories\Merchant\MerchantItemRepository;

class DestroyItemAction
{


    public function __construct(protected ItemRepository $modelR)
    {
        $this->modelR = $modelR;
    }


    public function handle(int $id, ?int $merchantId = null): bool
    {
        if ($merchantId !== null) {
            return $this->modelR->filter(['merchant_id' => $merchantId])
                ->whereKey($id)
                ->delete() > 0;
        }

        return $this->modelR->destroy($id);
    }
}
