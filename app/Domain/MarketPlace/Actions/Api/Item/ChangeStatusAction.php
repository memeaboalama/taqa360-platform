<?php

namespace App\Domain\MarketPlace\Actions\Api\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;

class ChangeStatusAction
{
    protected ItemRepository $itemRepository;


    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }


    public function handle(array $data)
    {
        $item = $this->itemRepository->findByCols(['id' => $data['item_id']]);
        $item->update(['status' => $data['status']]);
        return $item;
    }
}
