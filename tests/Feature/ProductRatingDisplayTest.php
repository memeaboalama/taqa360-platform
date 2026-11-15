<?php

namespace Tests\Feature;

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
}
