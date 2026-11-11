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
        config(['session.secure' => $request->isSecure()]);

        try {
            return $next($request);
        } finally {
            config(['session.secure' => $configuredSecureValue]);
        }
    }
}
