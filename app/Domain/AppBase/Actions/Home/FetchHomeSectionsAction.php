<?php

namespace App\Domain\AppBase\Actions\Home;

use App\Domain\AppBase\Models\AdPage;
use App\Domain\AppBase\Repositories\AdPlacementRepository;
use App\Domain\MarketPlace\Repositories\Merchant\ItemRepository;
use Illuminate\Support\Facades\Cache;

class FetchHomeSectionsAction
{
    public function __construct(
        private AdPlacementRepository $adPlacementRepository,
        private ItemRepository $itemRepository
    ) {}

    public function handle(): \Illuminate\Support\Collection
    {
        return Cache::remember('ecommerce_home_product_sections_' . app()->getLocale(), 1800, function() {
            $page = AdPage::where('key', 'ecommerce_home')->first();

            if (!$page) {
                return collect();
            }

            $placements = $this->adPlacementRepository->builder(order: 'asc', orderCol: 'sort_order')
                ->where('page_id', $page->id)
                ->where('status', 'active')
                ->whereIn('type', ['product_tabs', 'product_slider', 'product_sidebar'])
                ->with(['activeProductContents' => function($query) {
                    $query->with('product');
                }])
                ->get();

            return $placements;
        });
    }
}
