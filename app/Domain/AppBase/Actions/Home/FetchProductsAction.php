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
        return Cache::remember('storefront_featured_products_v2_' . app()->getLocale() . '_' . $limit, 1800, function() use ($limit) {
            return $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->take($limit)
                ->get();
        });
    }

    public function getNewProducts(int $limit = 6)
    {
        return Cache::remember('storefront_new_products_v2_' . app()->getLocale() . '_' . $limit, 1800, function() use ($limit) {
            return $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->latest('created_at')
                ->take($limit)
                ->get();
        });
    }

    public function getBestSellers(int $limit = 6)
    {
        return Cache::remember('storefront_best_sellers_v2_' . app()->getLocale() . '_' . $limit, 1800, function() use ($limit) {
            return $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->take($limit)
                ->get();
        });
    }

    public function getExclusiveProducts(int $limit = 6)
    {
        return Cache::remember('storefront_exclusive_products_v2_' . app()->getLocale() . '_' . $limit, 1800, function() use ($limit) {
            return $this->itemRepository->storefrontQuery(['status' => ActiveEnum::ACTIVE->value])
                ->take($limit)
                ->get();
        });
    }
}
