<?php

namespace Application\Marketplace\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMerchantPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure|null $next
     * @param null $permission
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector|mixed|Response
     */
    public function handle(Request $request, Closure $next, $permission = null): mixed
    {
        if (Auth::guard('merchant_employee')->check()) setPermissionsTeamId(Auth::guard('merchant_employee')->user()->merchant_id);
        if (Auth::guard('merchant')->check()) setPermissionsTeamId(Auth::guard('merchant')->user()->id);

        if (Auth::guard('merchant')->check()) {
            return $next($request);

        } elseif (Auth::guard('merchant_employee')->check() && Auth::guard('merchant_employee')->user()->hasAnyPermission($permission)) {
            return $next($request);
        }
        abort(403);
    }
}
