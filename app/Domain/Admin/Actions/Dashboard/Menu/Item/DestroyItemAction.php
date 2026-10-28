<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;

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
