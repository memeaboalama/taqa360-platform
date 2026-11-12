<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetSessionCookieSecurity
{
    public function handle(Request $request, Closure $next): Response
    {
        $configuredSecureValue = config('session.secure');
        $forwardedProto = strtolower(trim((string) $request->headers->get('x-forwarded-proto')));
        config(['session.secure' => $request->isSecure() || $forwardedProto === 'https']);

        try {
            return $next($request);
        } finally {
            config(['session.secure' => $configuredSecureValue]);
        }
    }
}
