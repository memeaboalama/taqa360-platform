<?php

namespace App\Domain\MarketPlace\Actions\Api\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Enum\StatusEnum;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Support\Collection;

class FetchItemsAction
{
    protected ItemRepository $itemRepository;


    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function handle($data = [], $merchant_id = null)
    {
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;
            $condition = ["status" => StatusEnum::ACTIVE->value];

            if($merchant_id) {
                $condition = ["status" => StatusEnum::ACTIVE->value, 'merchant_id' => $merchant_id];
            }

            return $this->itemRepository->paginate(
                cols: ['id', 'title', 'description', 'default_price', 'calories', 'preparing_time', 'image', 'status'],
                relations: ['additionGroups', 'additionGroups.choices'] ,
                condition: $condition,
                paginate: $per_page
            );
        }

        return $this->itemRepository->all();
    }

    public function getByCategory(int $category_id, $data = []) : CursorPaginator|\Illuminate\Database\Eloquent\Collection
    {
        if(isset($data['paginated']))
        {
            $per_page = isset($data['per_page']) ? $data['per_page'] : null;

            return $this->itemRepository->cursorPaginate(
                cols: ['id', 'title', 'description', 'default_price', 'calories', 'preparing_time', 'image', 'status'],
                relations: ['additionGroups', 'additionGroups.choices'] ,
                condition: ["status" => StatusEnum::ACTIVE->value, 'category_id' => $category_id],
                paginate: $per_page);
        }
        return $this->itemRepository->all(condition:['category_id' => $category_id]);
    }
}
