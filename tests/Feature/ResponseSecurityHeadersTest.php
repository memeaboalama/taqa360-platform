<?php

namespace Tests\Feature;

use App\Http\Middleware\ResponseSecurityHeaders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ResponseSecurityHeadersTest extends TestCase
{
    public function test_global_middleware_adds_security_headers_to_responses(): void
    {
        Route::get('/_test/security-headers', static fn () => response('ok'));

        $this->get('/_test/security-headers')
            ->assertOk()
            ->assertHeader(
                'Content-Security-Policy',
                "base-uri 'self'; object-src 'none'; frame-ancestors 'self'; upgrade-insecure-requests"
            )
            ->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    public function test_middleware_preserves_existing_content_security_policy(): void
    {
        $request = Request::create('/');
        $response = (new ResponseSecurityHeaders)->handle(
            $request,
            static fn () => new Response('', 200, ['Content-Security-Policy' => "default-src 'none'"])
        );

        $this->assertSame("default-src 'none'", $response->headers->get('Content-Security-Policy'));
    }

    public function test_middleware_adds_hsts_for_secure_requests_only(): void
    {
        $secureRequest = Request::create('/', 'GET', [], [], [], ['HTTPS' => 'on']);
        $secureResponse = (new ResponseSecurityHeaders)->handle($secureRequest, static fn () => new Response);

        $this->assertSame('max-age=31536000', $secureResponse->headers->get('Strict-Transport-Security'));

        $insecureRequest = Request::create('/');
        $insecureResponse = (new ResponseSecurityHeaders)->handle($insecureRequest, static fn () => new Response);

        $this->assertFalse($insecureResponse->headers->has('Strict-Transport-Security'));
    }

    public function test_middleware_adds_hsts_in_production_behind_a_tls_terminating_proxy(): void
    {
        $this->app->instance('env', 'production');
        $request = Request::create('/');
        $response = (new ResponseSecurityHeaders)->handle($request, static fn () => new Response);

        $this->assertSame('max-age=31536000', $response->headers->get('Strict-Transport-Security'));
    }
}
