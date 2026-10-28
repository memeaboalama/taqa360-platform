<?php

namespace App\Application\Admin\Middleware;

use App\Application\Admin\Providers\AdminRouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure|null $next
     * @return Response
     */
    public function handle(Request $request, Closure $next = null): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect(AdminRouteServiceProvider::LOGIN);
        }
        return $next($request);
    }
}
