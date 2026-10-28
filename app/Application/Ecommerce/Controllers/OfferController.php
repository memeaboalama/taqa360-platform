<?php

namespace App\Application\Ecommerce\Controllers;

use App\Domain\AppBase\Models\Offer;
use App\Domain\AppBase\Models\Promocode;
use App\Domain\AppBase\Models\Setting\Currency;
use App\Domain\MarketPlace\Models\Favorite;
use App\Http\Controllers\Controller;
use Domain\MarketPlace\Models\Catalog\Category;
use Domain\MarketPlace\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class OfferController extends Controller
{
    /**
     * Display the Special Offers hub page.
     * Products shown here are ONLY those explicitly marked is_on_sale = true
     * with a valid sale_price in the database.
     */
    public function index(Request $request)
    {
        // 1. Categories that have at least one on-sale product
        $categories = $this->getOfferCategories();

        // 2. Active promo vouchers / coupons from DB
        $vouchers = $this->getActiveVouchers();

        // 3. Fetch, filter, sort and paginate on-sale products
        $products = $this->getOnSaleProducts($request);

        // 4. User favourites (for heart icon state)
        $favoriteIds = Auth::check()
            ? Favorite::where('user_id', Auth::id())->pluck('item_id')->flip()->all()
            : [];

        return view('ecommerce::pages.offers', [
            'products'       => $products,
            'categories'     => $categories,
            'vouchers'       => $vouchers,
            'favoriteIds'    => $favoriteIds,
            'activeCategory' => $request->get('category', 'all'),
            'activeDiscount' => $request->get('discount_range', 'all'),
            'activeSort'     => $request->get('sort_by', 'discount_desc'),
            'searchQuery'    => $request->get('q', ''),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────────────────────────

    /**
     * Categories that contain at least one active on-sale item.
     */
    private function getOfferCategories()
    {
        return Category::query()
            ->where('is_active', true)
            ->whereHas('items', function ($q) {
                $q->where('status', 'active')
                  ->where('is_on_sale', true)
                  ->whereNotNull('sale_price')
                  ->where(function ($qq) {
                      $qq->whereNull('sale_expires_at')
                         ->orWhere('sale_expires_at', '>', now());
                  });
            })
            ->withCount(['items as sale_items_count' => function ($q) {
                $q->where('status', 'active')
                  ->where('is_on_sale', true)
                  ->whereNotNull('sale_price')
                  ->where(function ($qq) {
                      $qq->whereNull('sale_expires_at')
                         ->orWhere('sale_expires_at', '>', now());
                  });
            }])
            ->orderByDesc('sale_items_count')
            ->take(12)
            ->get();
    }

    /**
     * Active promo vouchers – pulled from the `offers` table first,
     * then `promocodes` as fallback; returns generic cards if both empty.
     */
    private function getActiveVouchers(): array
    {
        $vouchers = [];

        // ── 1. offers table ──────────────────────────────────────
        try {
            if (Schema::hasTable('offers')) {
                Offer::query()
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
                    })
                    ->take(4)
                    ->get()
                    ->each(function ($offer) use (&$vouchers) {
                        $discountLabel = $offer->type === 'percent'
                            ? $offer->discount_value . '%'
                            : $offer->discount_value . ' ' . Currency::symbol();

                        $vouchers[] = [
                            'code'       => strtoupper(preg_replace('/[^A-Z0-9]/i', '', $offer->name) ?: 'OFFER' . $offer->id),
                            'title'      => $offer->name,
                            'subtitle'   => $offer->note ?: ($offer->min_order_value
                                ? __('ecommerce::ecommerce.min_order') . ' ' . Currency::format($offer->min_order_value)
                                : ''),
                            'badge'      => $discountLabel . ' ' . __('ecommerce::ecommerce.off'),
                            'expires_at' => $offer->expired_at?->format('Y-m-d'),
                            'min_order'  => $offer->min_order_value,
                        ];
                    });
            }
        } catch (\Throwable) {}

        // ── 2. promocodes table ──────────────────────────────────
        if (empty($vouchers)) {
            try {
                if (Schema::hasTable('promocodes')) {
                    Promocode::query()
                        ->where('status', 'active')
                        ->where(function ($q) {
                            $q->whereNull('expired_at')->orWhere('expired_at', '>=', now());
                        })
                        ->take(4)
                        ->get()
                        ->each(function ($promo) use (&$vouchers) {
                            $discountLabel = $promo->type === 'percent'
                                ? $promo->discount_value . '%'
                                : $promo->discount_value . ' ' . Currency::symbol();

                            $vouchers[] = [
                                'code'       => $promo->code,
                                'title'      => __('ecommerce::ecommerce.special_offer_badge') . ': ' . $promo->code,
                                'subtitle'   => $promo->min_order_value
                                    ? __('ecommerce::ecommerce.min_order') . ' ' . Currency::format($promo->min_order_value)
                                    : '',
                                'badge'      => $discountLabel . ' ' . __('ecommerce::ecommerce.off'),
                                'expires_at' => $promo->expired_at?->format('Y-m-d'),
                                'min_order'  => $promo->min_order_value,
                            ];
                        });
                }
            } catch (\Throwable) {}
        }

        return $vouchers;
    }

    /**
     * Fetch on-sale products from the database, applying all filters
     * (category, discount range, text search) and sort, then paginate.
     */
    private function getOnSaleProducts(Request $request)
    {
        $query = Item::query()
            ->where('status', 'active')
            ->onSale()                              // uses the scope we added to Item
            ->with(['merchant', 'mainCategory']);

        // ── Category filter ──────────────────────────────────────
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('main_category_id', $request->category);
        }

        // ── Text search ──────────────────────────────────────────
        if ($request->filled('q') && trim($request->q) !== '') {
            $term = trim($request->q);
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%");
            });
        }

        // ── Discount range filter ────────────────────────────────
        $range = $request->get('discount_range', 'all');
        if ($range === '50_plus') {
            // sale_price <= 50% of default_price  →  discount >= 50%
            $query->whereRaw('(default_price - sale_price) / default_price * 100 >= ?', [50]);
        } elseif ($range === '30_to_50') {
            $query->whereRaw('(default_price - sale_price) / default_price * 100 >= ?', [30])
                  ->whereRaw('(default_price - sale_price) / default_price * 100 < ?', [50]);
        } elseif ($range === '10_to_30') {
            $query->whereRaw('(default_price - sale_price) / default_price * 100 >= ?', [10])
                  ->whereRaw('(default_price - sale_price) / default_price * 100 < ?', [30]);
        } elseif ($range === 'flash') {
            // Flash = expires within the next 24 hours
            $query->whereNotNull('sale_expires_at')
                  ->where('sale_expires_at', '<=', now()->addHours(24));
        }

        // ── Sorting ──────────────────────────────────────────────
        $sort = $request->get('sort_by', 'discount_desc');
        match ($sort) {
            'discount_desc' => $query->orderByRaw('(default_price - sale_price) / default_price DESC'),
            'price_asc'     => $query->orderBy('sale_price', 'asc'),
            'price_desc'    => $query->orderBy('sale_price', 'desc'),
            'oldest'        => $query->orderBy('created_at', 'asc'),
            default         => $query->orderBy('created_at', 'desc'),
        };

        // ── Paginate ─────────────────────────────────────────────
        $perPage = (int) $request->get('per_page', 16);

        return $query->paginate($perPage)->withQueryString();
    }
}
