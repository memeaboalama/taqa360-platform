<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Domain\MarketPlace\Models\Item;
use Tests\TestCase;

class ProductRatingDisplayTest extends TestCase
{
    public function test_products_without_reviews_do_not_show_a_rating(): void
    {
        $html = view('ecommerce::partials.product.rating', [
            'product' => (object) ['rate' => 4.8, 'reviews_count' => 0],
        ])->render();

        $this->assertStringContainsString(__('ecommerce::ecommerce.no_reviews_yet'), $html);
        $this->assertStringNotContainsString('fa-star', $html);
    }

    public function test_products_with_reviews_show_their_rating_and_review_count(): void
    {
        $html = view('ecommerce::partials.product.rating', [
            'product' => (object) ['rate' => 4.5, 'reviews_count' => 2],
        ])->render();

        $this->assertStringContainsString('4.5', $html);
        $this->assertStringContainsString('2', $html);
        $this->assertStringContainsString('fa-star', $html);
    }

    public function test_sidebar_product_card_uses_the_no_reviews_state(): void
    {
        Cache::put('store_currency_iso', 'sar');

        $html = view('ecommerce::components.sidebar-product-card', [
            'product' => (object) [
                'id' => 1,
                'title' => 'Product',
                'image_path' => '',
                'default_price' => 100,
                'rate' => 4.8,
                'reviews_count' => 0,
            ],
        ])->render();

        $this->assertStringContainsString(__('ecommerce::ecommerce.no_reviews_yet'), $html);
        $this->assertStringNotContainsString('fa-star', $html);
    }

    public function test_item_rating_summary_aggregates_approved_reviews_only(): void
    {
        $query = Item::query()->withReviewSummary();
        $sql = strtolower($query->toSql());
        $approvedBindings = array_filter($query->getBindings(), fn ($value) => $value === 'approved');

        $this->assertStringContainsString('product_reviews', $sql);
        $this->assertStringContainsString('avg(', $sql);
        $this->assertStringContainsString('count(*)', $sql);
        $this->assertCount(2, $approvedBindings);
    }
}
