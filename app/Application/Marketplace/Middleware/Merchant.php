<?php

namespace Application\Marketplace\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Merchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        app()->instance('_InitialMerchant', false);
        app()->instance('_InitialMerchantName', false);
        app()->instance('_InitialMerchantCompanyName', false);
        if (Auth::guard($guard)->check()) {
            app()->instance('_InitialMerchant', Auth::guard($guard)->user()->id);
            app()->instance('_InitialMerchantName', Auth::guard($guard)->user()?->user_name ?? "");
            app()->instance('_InitialMerchantCompanyName', Auth::guard($guard)->user()?->company_name ?? "");
            return $next($request);
        }
        else if (Auth::guard('merchant')->check()){
            app()->instance('_InitialMerchant', Auth::guard('merchant')->user()->id);
            app()->instance('_InitialMerchantName', Auth::guard('merchant')->user()?->user_name ?? "");
            app()->instance('_InitialMerchantCompanyName', Auth::guard('merchant')->user()->company_name ?? "");
            return $next($request);
        
        }
        else if (Auth::guard('merchant_employee')->check()){
            app()->instance('_InitialMerchant', Auth::guard('merchant_employee')->user()->merchant_id);
            app()->instance('_InitialMerchantName', Auth::guard('merchant_employee')->user()?->name ?? "");
            app()->instance('_InitialMerchantCompanyName', Auth::guard('merchant_employee')->user()->merchant()->first()?->company_name ?? "");
            return $next($request);
        }

        return redirect(RouteServiceProvider::MARKETPLACE_LOGIN);
    }
}
