<?php

namespace App\Domain\AppBase\Actions\Home;

use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use App\Support\Enum\ActiveEnum;
use Illuminate\Support\Facades\Cache;

class FetchSidebarDataAction
{
    public function __construct(
        private AdPlacementRepository $adPlacementRepository,
        private ItemRepository $itemRepository
    ) {}

    public function getNewProducts(int $limit = 9)
    {
        $placement = $this->adPlacementRepository->builder()
            ->where('key', 'sidebar_new_products')
            ->where('status', 'active')
            ->with('activeBanners')
            ->first();

        if ($placement && $placement->activeBanners->isNotEmpty()) {
            $productIds = $placement->activeBanners
                ->map(fn($banner) => $banner->click_action['id'] ?? null)
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (!empty($productIds)) {
                return $this->itemRepository->builder(['*'], [], [], 'desc')
                    ->whereIn('id', $productIds)
                    ->get();
            }
        }

        return Cache::remember("sidebar_new_products_fallback_" . app()->getLocale(), 1800, function() use ($limit) {
            return $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value], 'desc')
                ->take($limit)
                ->get();
        });
    }

    public function getRecentlyViewed(int $limit = 9)
    {
        $placement = $this->adPlacementRepository->builder()
            ->where('key', 'sidebar_recently_viewed')
            ->where('status', 'active')
            ->with('activeBanners')
            ->first();

        if ($placement && $placement->activeBanners->isNotEmpty()) {
            $productIds = $placement->activeBanners
                ->map(fn($banner) => $banner->click_action['id'] ?? null)
                ->filter()
                ->unique()
                ->values()
                ->toArray();

            if (!empty($productIds)) {
                return $this->itemRepository->builder(['*'], [], [], 'desc')
                    ->whereIn('id', $productIds)
                    ->get();
            }
        }

        return Cache::remember("sidebar_recently_viewed_fallback_" . app()->getLocale(), 1800, function() use ($limit) {
            return $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value])
                ->inRandomOrder()
                ->take($limit)
                ->get();
        });
    }

    public function getSuggestedProducts(int $limit = 6)
    {
        return Cache::remember('sidebar_suggested_products_' . app()->getLocale(), 1800, function() use ($limit) {
            return $this->itemRepository->builder(['*'], [], ['status' => ActiveEnum::ACTIVE->value])
                ->inRandomOrder()
                ->take($limit)
                ->get();
        });
    }

    public function getServices()
    {
        $placement = $this->adPlacementRepository->builder()
            ->where('key', 'sidebar_services')
            ->where('status', 'active')
            ->with('activeBanners')
            ->first();

        return $placement ? $placement->activeBanners : collect();
    }
}
