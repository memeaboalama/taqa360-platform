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


    public function handle(int $id): bool
    {
        return $this->modelR->destroy($id);
    }
}
