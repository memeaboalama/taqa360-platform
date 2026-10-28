<?php

namespace Application\Driver\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DriverRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next = null): Response
    {
        if (Auth::guard('driver')->check()) {
            return redirect(RouteServiceProvider::DRIVER_HOME);
        }
        return $next($request);
    }
}
