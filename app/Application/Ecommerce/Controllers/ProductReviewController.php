<?php

namespace App\Application\Ecommerce\Controllers;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Domain\MarketPlace\Models\MerchantOrderItem;
use App\Domain\MarketPlace\Models\ProductReview;
use App\Http\Controllers\Controller;
use Domain\MarketPlace\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function store(Request $request, int $itemId): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title' => ['required', 'string', 'min:2', 'max:120'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $product = Item::query()
            ->whereKey($itemId)
            ->where('status', 'active')
            ->availableForStorefront()
            ->firstOrFail();
        $user = $request->user();

        if (ProductReview::query()->where('item_id', $product->id)->where('user_id', $user->id)->exists()) {
            return back()->withErrors([
                'rating' => __('ecommerce::ecommerce.product_review_already_submitted'),
            ])->withInput();
        }

        $orderItem = MerchantOrderItem::query()
            ->where('item_id', $product->id)
            ->whereHas('order', fn ($query) => $query
                ->where('user_id', $user->id)
                ->where('status', MerchantOrderStatuses::PREVIOUS->value))
            ->latest('id')
            ->first();

        if (! $orderItem) {
            return back()->withErrors([
                'purchase' => __('ecommerce::ecommerce.product_review_purchase_required'),
            ])->withInput();
        }

        ProductReview::query()->create([
            'item_id' => $product->id,
            'user_id' => $user->id,
            'merchant_order_item_id' => $orderItem->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'comment' => $validated['comment'],
            'status' => 'pending',
        ]);

        return back()->with('product_review_submitted', true);
    }
}
