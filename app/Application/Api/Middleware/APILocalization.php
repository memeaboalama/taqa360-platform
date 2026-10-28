<?php

namespace App\Application\Api\Middleware;

use Closure;
use Illuminate\Http\Request;

class APILocalization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (request()->is('api/*')) {
            $language = ($request->hasHeader('x-localization')) ? $request->header('x-localization') : 'ar';
            app()->setLocale($language);
        }

        return $next($request);
    }
}
