<?php

namespace Application\Driver\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // if (Auth::guard('driver')->check() && Auth::guard('driver')->user()->email_verified_at == null)
        // {
        //     return redirect(RouteServiceProvider::DRIVER_VERIFY_EMAIL);
        // }else{
        //     return $next($request);
        // }

       if (Auth::guard('driver')->user()->driverActive()) {
           return $next($request);
       }else{
           return redirect()->route('driver:profile');
       }
    }
}
