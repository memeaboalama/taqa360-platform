<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

class ErrorResponseSafetyTest extends TestCase
{
    public function test_html_errors_are_generic_and_offer_a_return_link(): void
    {
        config(['app.debug' => false]);

        Route::get('/_test/error-page', static function () {
            throw new RuntimeException('private trace marker');
        });

        $this->get('/_test/error-page')
            ->assertStatus(500)
            ->assertDontSee('private trace marker')
            ->assertSee('العودة إلى الصفحة الرئيسية')
            ->assertSee('href="' . url('/') . '"', false);
    }

    public function test_json_errors_do_not_expose_exception_details(): void
    {
        config(['app.debug' => false]);

        Route::get('/_test/json-error', static function () {
            throw new RuntimeException('private trace marker');
        });

        $this->getJson('/_test/json-error')
            ->assertStatus(500)
            ->assertJsonPath('message', 'Unexpected Exception. Try later')
            ->assertDontSee('private trace marker');
    }

    public function test_api_root_is_not_registered_as_a_dashboard_page(): void
    {
        config(['app.debug' => false]);

        $this->get('/api')
            ->assertNotFound()
            ->assertSee('العودة للصفحة السابقة')
            ->assertSee('href="' . route('ecommerce.static') . '"', false);

        $this->getJson('/api')
            ->assertNotFound()
            ->assertJsonPath('message', 'The specified URL cannot be found');
    }

    public function test_unknown_storefront_page_uses_store_theme_and_provides_back_navigation(): void
    {
        config(['app.debug' => false]);

        $this->get('/ar/nonexistent-page-xyz')
            ->assertNotFound()
            ->assertSee('store-navigation')
            ->assertSee('class="error-page container"', false)
            ->assertSee('id="error-go-back"', false)
            ->assertSee('العودة للصفحة السابقة')
            ->assertSee('href="' . route('ecommerce.static') . '"', false);
    }
}
