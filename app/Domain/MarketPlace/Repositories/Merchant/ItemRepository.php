<?php

namespace App\Domain\MarketPlace\Repositories\Merchant;

use App\Support\Enum\ActiveEnum;
use App\Support\Repositories\Main\EloquentMainRepository;
use Domain\MarketPlace\Models\Item;
use Domain\MarketPlace\Models\MerchantCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ItemRepository extends EloquentMainRepository
{
    public function __construct(Item $model)
    {
        parent::__construct($model);
    }

    public function filter($filters = [], array $with = []): Builder
    {
        $query = $this->modal
            ->with($with)
            ->newQuery();

        if(isset($filters['merchant_id']) && $filters['merchant_id'] !== null)
            $query->where('merchant_id', $filters['merchant_id']);

        if (isset($filters['item_id']) && $filters['item_id'] !== null)
            $query->where('id', $filters['item_id']);

        if (isset($filters['status']) && $filters['status'] !== null)
            $query->where('status', $filters['status']);

        if (isset($filters['main_category_id']) && $filters['main_category_id'] !== null)
            $query->where('main_category_id', $filters['main_category_id']);

        if (isset($filters['sub_category_id']) && $filters['sub_category_id'] !== null)
            $query->where('sub_category_id', $filters['sub_category_id']);

        if (isset($filters['child_category_id']) && $filters['child_category_id'] !== null)
            $query->where('child_category_id', $filters['child_category_id']);

        return $query;
    }

    public function get_categories_by_branch(array $cols = ['*'], array $relations = [], array $condition = [], string $order = 'asc', array $ids = []): Collection
    {
        return $this->builder($cols, $relations, $condition, $order)->active()->find($ids);
    }

    public function getItem(array $cols = ['*'], array $relations = [], array $condition = [], array $data = []): Collection
    {
        return $this->builder($cols, $relations, $condition)->active()->find($data['id']);
    }

    public function getCategoriesByMerchantId(int $merchant_id): Builder
    {
        return $this->builder()->where('merchant_id', $merchant_id);
    }

    public function getAllItemsAsBuilder(array $cols, array $with): Builder
    {
        return $this->builder($cols, $with, [], 'desc');
    }

    public function search(array $cols, string $term): Collection
    {
        return $this->modal::select($cols)
            ->where('title', 'like', '%' . $term . '%')
            ->limit(20)
            ->get();
    }

    public function count(array $cols)
    {
        return $this->modal->where($cols)->count();
    }

public function countMenuItems(int $merchant_id): int
    {
        return $this->modal::where('merchant_id', $merchant_id)
            ->where('status', ActiveEnum::ACTIVE->value)
            ->count();
    }

    public function getsByMerchant(int $merchant_id)
    {
        return $this->builder()->where('merchant_id', $merchant_id)->active();
    }

    public function getActiveItemsByMerchant(int $merchant_id, array $with = []): Builder
    {
        return $this->modal->with($with)
                          ->where('merchant_id', $merchant_id)
                          ->where('status', 'active');
    }

    public function filterMerchantItems(array $filters = [], int $merchant_id, array $with = []): Builder
    {
        $query = $this->getActiveItemsByMerchant($merchant_id, $with);

        if (!empty($filters['q'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['q'] . '%');
            });
        }

        if (isset($filters['category']) && $filters['category'] !== 'all') {
            $query->where(function($q) use ($filters) {
                $q->where('sub_category_id', $filters['category']);
                //   ->orWhere('sub_category_id', $filters['category'])
                //   ->orWhere('child_category_id', $filters['category']);
            });
        }

        if (isset($filters['main_category_id']) && $filters['main_category_id'] !== 'all') {
            $query->where('main_category_id', $filters['main_category_id']);
        }

        if (isset($filters['sub_category_id']) && $filters['sub_category_id'] !== 'all') {
            $query->where('sub_category_id', $filters['sub_category_id']);
        }

        return $query;
    }

    public function applySorting(Builder $query, string $sortBy = 'latest'): Builder
    {
        switch ($sortBy) {
            case 'latest':
                return $query->orderBy('created_at', 'desc');
            case 'oldest':
                return $query->orderBy('created_at', 'asc');
            case 'price_low':
                return $query->orderBy('default_price', 'asc');
            case 'price_high':
                return $query->orderBy('default_price', 'desc');
            case 'name_asc':
                return $query->orderBy('title', 'asc');
            case 'name_desc':
                return $query->orderBy('title', 'desc');
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }

    public function getFilteredItems(array $filters = [], int $merchant_id): Collection|LengthAwarePaginator
    {
        $with = [
            'merchant',
            'mainCategory',
            'subCategory',
            'childCategory',
            'files'
        ];

        $query = $this->filterMerchantItems($filters, $merchant_id, $with);
        $query = $this->applySorting($query, $filters['sort_by'] ?? 'latest');

        if (isset($filters['paginated']) && $filters['paginated']) {
            $perPage = $filters['per_page'] ?? 12;
            $page = $filters['page'] ?? 1;
            return $query->paginate($perPage, ['*'], 'page', $page);
        }

        return $query->get();
    }

    public function countByMerchantAndCategory(int $merchant_id, $category_id = null): int
    {
        $query = $this->getActiveItemsByMerchant($merchant_id);

        if ($category_id && $category_id !== 'all') {
            $query->where(function($q) use ($category_id) {
                $q->where('main_category_id', $category_id)
                  ->orWhere('sub_category_id', $category_id)
                  ->orWhere('child_category_id', $category_id);
            });
        }

        return $query->count();
    }

    public function getCategoryCounts(int $merchant_id, $categories): array
    {
        $counts = ['all' => 0];

        try {
            $counts['all'] = $this->countByMerchantAndCategory($merchant_id);

            foreach ($categories as $category) {
                $counts[$category->id] = $this->countByMerchantAndCategory($merchant_id, $category->id);
            }
        } catch (\Exception $e) {
            $counts['all'] = 0;
            foreach ($categories as $category) {
                $counts[$category->id] = 0;
            }
        }

        return $counts;
    }
}
