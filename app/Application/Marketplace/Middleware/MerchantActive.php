<?php

namespace Application\Marketplace\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MerchantActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // if (Auth::guard('merchant')->check() && Auth::guard('merchant')->user()->email_verified_at == null)
        // {
        //     return redirect(RouteServiceProvider::MARKETPLACE_VERIFY_EMAIL);
        // }else{
        //     return $next($request);
        // }

//        if (Auth::guard('merchant')->user()->merchantActive()) {
//            return $next($request);
//        }else{
//            return redirect()->route('marketplace:profile');
//        }
     return $next($request);
    }
}
