<?php

namespace App\Domain\MarketPlace\Actions\Api\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;

class FetchSingleItemAction
{
    protected ItemRepository $itemRepository;


    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }


    public function handle(int $id): \Illuminate\Database\Eloquent\Model|null
    {
        return $this->itemRepository->findByCols(['id' => $id]);
    }
}
