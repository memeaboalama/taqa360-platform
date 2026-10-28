<?php

namespace App\Domain\AppBase\Actions\Home;

use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Enum\ActiveEnum;
use Illuminate\Support\Facades\Cache;

class FetchProductsAction
{
    public function __construct(
        private ItemRepository $itemRepository
    ) {}

    public function getFeaturedProducts(int $limit = 8)
    {
        return Cache::remember('featured_products_' . app()->getLocale(), 1800, function() use ($limit) {
            return $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                // ->where('is_featured', true)
                ->take($limit)
                ->get();
        });
    }

    public function getNewProducts(int $limit = 6)
    {
        return Cache::remember('new_products_' . app()->getLocale(), 1800, function() use ($limit) {
            return $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                ->latest('created_at')
                ->take($limit)
                ->get();
        });
    }

    public function getBestSellers(int $limit = 6)
    {
        return Cache::remember('best_sellers_' . app()->getLocale(), 1800, function() use ($limit) {
            return $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                // ->orderBy('views_count', 'desc')
                ->take($limit)
                ->get();
        });
    }

    public function getExclusiveProducts(int $limit = 6)
    {
        return Cache::remember('exclusive_products_' . app()->getLocale(), 1800, function() use ($limit) {
            return $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                // ->where('is_exclusive', true)
                ->take($limit)
                ->get();
        });
    }
}
