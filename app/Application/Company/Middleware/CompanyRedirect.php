<?php

namespace Application\Company\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CompanyRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next = null): Response
    {
        if (Auth::guard('company')->check()) {
            return redirect(RouteServiceProvider::COMPANY_HOME);
        }
        return $next($request);
    }
}
