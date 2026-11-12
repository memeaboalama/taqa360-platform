<?php

namespace Tests\Feature;

use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath;
use Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect;
use Tests\TestCase;

class AdminLoginSessionCookieTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            LocaleSessionRedirect::class,
            LaravelLocalizationRedirectFilter::class,
            LaravelLocalizationViewPath::class,
        ]);
        config(['session.driver' => 'file']);
    }

    public function test_session_cookie_has_secure_attribute_for_https_requests(): void
    {
        $response = $this->call('GET', 'https://localhost/admin/login');
        $cookie = $this->sessionCookie($response->headers->getCookies());

        $this->assertNotNull($cookie);
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertSame('lax', $cookie->getSameSite());
    }

    public function test_session_cookie_remains_insecure_for_http_requests(): void
    {
        $response = $this->call('GET', 'http://localhost/admin/login');
        $cookie = $this->sessionCookie($response->headers->getCookies());

        $this->assertNotNull($cookie);
        $this->assertFalse($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertSame('lax', $cookie->getSameSite());
    }

    public function test_session_cookie_is_secure_when_https_is_terminated_by_a_proxy(): void
    {
        $response = $this->call(
            'GET',
            'http://localhost/admin/login',
            [],
            [],
            [],
            ['HTTP_X_FORWARDED_PROTO' => 'https']
        );
        $cookie = $this->sessionCookie($response->headers->getCookies());

        $this->assertNotNull($cookie);
        $this->assertTrue($cookie->isSecure());
        $this->assertTrue($cookie->isHttpOnly());
        $this->assertSame('lax', $cookie->getSameSite());
    }

    /**
     * @param  array<int, \Symfony\Component\HttpFoundation\Cookie>  $cookies
     */
    private function sessionCookie(array $cookies): ?\Symfony\Component\HttpFoundation\Cookie
    {
        foreach ($cookies as $cookie) {
            if ($cookie->getName() === config('session.cookie')) {
                return $cookie;
            }
        }

        return null;
    }
}
