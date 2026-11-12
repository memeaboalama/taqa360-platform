<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResponseSecurityHeaders
{
    private const CONTENT_SECURITY_POLICY =
        "base-uri 'self'; object-src 'none'; frame-ancestors 'self'; upgrade-insecure-requests";

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'Content-Security-Policy' => self::CONTENT_SECURITY_POLICY,
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
        ];

        foreach ($headers as $name => $value) {
            if (!$response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        if (($request->isSecure() || app()->environment('production'))
            && !$response->headers->has('Strict-Transport-Security')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }
}
