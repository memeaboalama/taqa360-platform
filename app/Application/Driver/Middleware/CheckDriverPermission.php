<?php

namespace Application\Driver\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckDriverPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure|null $next
     * @param null $permission
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector|mixed|Response
     */
    public function handle(Request $request, Closure $next = null, $permission = null): mixed
    {
        if (Auth::guard('driver_employee')->check()) setPermissionsTeamId(Auth::guard('driver_employee')->user()->driver_id);
        if (Auth::guard('driver')->check()) setPermissionsTeamId(Auth::guard('driver')->user()->id);

        if (Auth::guard('driver')->check()) {
            return $next($request);

        } elseif (Auth::guard('driver_employee')->check() && Auth::guard('driver_employee')->user()->hasAnyPermission($permission)) {
            return $next($request);
        }
        abort(403);
    }
}
