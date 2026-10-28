<?php

namespace App\Application\Company\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyPermission
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
        if (Auth::guard('company_employee')->check()) setPermissionsTeamId(Auth::guard('company_employee')->user()->company_id);
        if (Auth::guard('company')->check()) setPermissionsTeamId(Auth::guard('company')->user()->id);

        if (Auth::guard('company')->check()) {
            return $next($request);

        } elseif (Auth::guard('company_employee')->check() && Auth::guard('company_employee')->user()->hasAnyPermission($permission)) {
            return $next($request);
        }
        abort(403);
    }
}
