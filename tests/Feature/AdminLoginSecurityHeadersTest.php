<?php

namespace Tests\Feature;

use App\Http\Middleware\AdminLoginSecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AdminLoginSecurityHeadersTest extends TestCase
{
    public function test_admin_login_route_uses_security_headers_middleware(): void
    {
        $route = Route::getRoutes()->getByName('admin.login');

        $this->assertNotNull($route);
        $this->assertContains(AdminLoginSecurityHeaders::class, $route->gatherMiddleware());
    }

    public function test_admin_login_response_contains_security_headers(): void
    {
        $this->withoutMiddleware([
            LocaleSessionRedirect::class,
            LaravelLocalizationRedirectFilter::class,
            LaravelLocalizationViewPath::class,
        ]);

        $this->get('/admin/login')
            ->assertOk()
            ->assertHeader('Content-Security-Policy')
            ->assertHeader('Strict-Transport-Security', 'max-age=31536000')
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY');
    }

    public function test_middleware_sets_security_headers_for_secure_responses(): void
    {
        $request = Request::create('/ar/admin/login', 'GET', [], [], [], ['HTTPS' => 'on']);
        $response = (new AdminLoginSecurityHeaders)->handle(
            $request,
            static fn () => new Response
        );

        $this->assertSame(
            "default-src 'self'; base-uri 'self'; object-src 'none'; form-action 'self'; frame-ancestors 'none'; ".
            "script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; ".
            "font-src 'self' data: https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self'",
            $response->headers->get('Content-Security-Policy')
        );
        $this->assertSame('max-age=31536000', $response->headers->get('Strict-Transport-Security'));
        $this->assertSame('strict-origin-when-cross-origin', $response->headers->get('Referrer-Policy'));
        $this->assertSame('nosniff', $response->headers->get('X-Content-Type-Options'));
        $this->assertSame('DENY', $response->headers->get('X-Frame-Options'));
    }

    public function test_middleware_does_not_set_hsts_for_insecure_responses(): void
    {
        $request = Request::create('/ar/admin/login');
        $response = (new AdminLoginSecurityHeaders)->handle(
            $request,
            static fn () => new Response
        );

        $this->assertFalse($response->headers->has('Strict-Transport-Security'));
    }
}
