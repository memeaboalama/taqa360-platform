<?php

namespace Application\Company\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Company
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next = null, $guard = null)
    {
        app()->instance('_InitialCompany', false);
        app()->instance('_InitialCompanyName', false);
        app()->instance('_InitialCompanyCompanyName', false);
        if (Auth::guard($guard)->check()) {
            app()->instance('_InitialCompany', Auth::guard($guard)->user()->id);
            app()->instance('_InitialCompanyName', Auth::guard($guard)->user()?->company_name ?? "");
            return $next($request);
        }elseif (Auth::guard('company_employee')->check()){
            app()->instance('_InitialCompany', Auth::guard('company_employee')->user()->company_id);
            app()->instance('_InitialCompanyName', Auth::guard('company_employee')->user()?->company_name ?? "");
            return $next($request);
        }

        return redirect(RouteServiceProvider::COMPANY_LOGIN);
    }
}
