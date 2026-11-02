<?php

namespace App\Domain\MarketPlace\Actions\Api\Item;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Enum\StatusEnum;
use Domain\MarketPlace\Models\Item;
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Support\Collection;

class FetchItemsAction
{
    protected ItemRepository $itemRepository;


    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function handle($data = [], $merchant_id = null, bool $storefrontOnly = false)
    {
        if ($storefrontOnly) {
            $query = Item::query()
                ->select(['id', 'title', 'description', 'default_price', 'calories', 'preparing_time', 'image', 'status'])
                ->where('status', StatusEnum::ACTIVE->value)
                ->availableForStorefront()
                ->with(['additionGroups', 'additionGroups.choices'])
                ->orderBy('id');

            if ($merchant_id) {
                $query->where('merchant_id', $merchant_id);
            }

            if (isset($data['paginated'])) {
                return $query->paginate($data['per_page'] ?? 10);
            }

            return $query->get();
        }

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

    public function getByCategory(int $category_id, $data = [], bool $storefrontOnly = false) : CursorPaginator|\Illuminate\Database\Eloquent\Collection|\Illuminate\Pagination\LengthAwarePaginator
    {
        if ($storefrontOnly) {
            $query = Item::query()
                ->select(['id', 'title', 'description', 'default_price', 'calories', 'preparing_time', 'image', 'status'])
                ->where('status', StatusEnum::ACTIVE->value)
                ->where('category_id', $category_id)
                ->availableForStorefront()
                ->with(['additionGroups', 'additionGroups.choices'])
                ->orderBy('id');

            if (isset($data['paginated'])) {
                return $query->cursorPaginate($data['per_page'] ?? 10);
            }

            return $query->get();
        }

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
