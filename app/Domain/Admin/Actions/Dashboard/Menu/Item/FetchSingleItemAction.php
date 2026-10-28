<?php

namespace App\Domain\Admin\Actions\Dashboard\Menu\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;

class FetchSingleItemAction
{
    public function __construct(protected ItemRepository $modelR)
    {
        $this->modelR = $modelR;
    }

    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->modelR->findByCols(['id' => $id]);
    }

}
