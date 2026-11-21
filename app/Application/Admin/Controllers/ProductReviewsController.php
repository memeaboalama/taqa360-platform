<?php

namespace App\Application\Admin\Controllers;

use App\Domain\MarketPlace\Models\ProductReview;
use App\Http\Controllers\Controller;
use Domain\MarketPlace\Models\Item;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;

class ProductReviewsController extends Controller
{
    public function index(): View
    {
        return view('admin::pages.product-reviews.index', [
            'reviews' => ProductReview::query()
                ->with(['item', 'user'])
                ->orderByRaw("status = 'pending' DESC")
                ->latest()
                ->paginate(30),
        ]);
    }

    public function approve(ProductReview $review): RedirectResponse
    {
        $review->status = 'approved';
        $review->save();
        $this->clearRatingCache($review->item()->first());

        return back()->with('success', __('admin::lang.Product Review Approved'));
    }

    public function reject(ProductReview $review): RedirectResponse
    {
        $review->status = 'rejected';
        $review->save();
        $this->clearRatingCache($review->item()->first());

        return back()->with('success', __('admin::lang.Product Review Rejected'));
    }

    private function clearRatingCache(?Item $item): void
    {
        foreach (['ar', 'en', app()->getLocale()] as $locale) {
            Cache::forget("home_page_data_storefront_v5_{$locale}");
            Cache::forget("storefront_featured_products_v3_{$locale}_8");
            Cache::forget("storefront_new_products_v3_{$locale}_6");
            Cache::forget("storefront_best_sellers_v3_{$locale}_6");
            Cache::forget("storefront_exclusive_products_v3_{$locale}_6");
            Cache::forget("storefront_sidebar_new_products_v3_{$locale}_9");
            Cache::forget("storefront_sidebar_recently_viewed_v3_{$locale}_9");
            Cache::forget("storefront_sidebar_suggested_products_v3_{$locale}_6");
        }

        if (! $item) {
            return;
        }

        Cache::forget("storefront_product_details_{$item->id}_v5");

        if ($item->main_category_id) {
            Item::query()
                ->where('main_category_id', $item->main_category_id)
                ->pluck('id')
                ->each(fn ($itemId) => Cache::forget("storefront_related_products_{$itemId}_v3"));
        }
    }
}
