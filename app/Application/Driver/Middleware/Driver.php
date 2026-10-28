<?php

namespace Application\Driver\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Driver
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        app()->instance('_InitialDriver', false);
        app()->instance('_InitialDriverName', false);
        app()->instance('_InitialDriverCompanyName', false);
        if (Auth::guard($guard)->check()) {
            $driver_auth_user=Auth::guard($guard)->user();
            if (!($driver_auth_user && $driver_auth_user instanceof \Domain\Driver\Models\Driver))
            return redirect(RouteServiceProvider::DRIVER_LOGIN);
            app()->instance('_InitialDriver', $driver_auth_user?->id??0);
            app()->instance('_InitialDriverName', $driver_auth_user?->user_name ?? "");
            app()->instance('_InitialDriverCompanyName', $driver_auth_user?->company()?->company_name ?? "");
            return $next($request);
        }elseif (Auth::guard('driver')->check()){
            $driver_auth_user=Auth::guard('driver')->user();
            if (!($driver_auth_user && $driver_auth_user instanceof \Domain\Driver\Models\Driver))
            return redirect(RouteServiceProvider::DRIVER_LOGIN);
            app()->instance('_InitialDriver', $driver_auth_user?->id);
            app()->instance('_InitialDriverName', $driver_auth_user?->name ?? "");
            app()->instance('_InitialDriverCompanyName', $driver_auth_user?->company()?->company_name ?? "");
            return $next($request);
        }

        return redirect(RouteServiceProvider::DRIVER_LOGIN);
    }
}
