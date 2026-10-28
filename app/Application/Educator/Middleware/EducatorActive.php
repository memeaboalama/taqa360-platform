<?php

namespace Application\Educator\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducatorActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // ---------------------------------------------------------------------------
        // [DISABLED - email verification, comment-only] Unverified educators were
        // redirected to the /verify-email page, which blocked them from /profile and
        // /profile-builder. Commented out so an educator without email_verified_at can
        // keep using the app without entering a confirmation code.
        // Kept commented (not deleted): uncomment everything below to re-enforce it.
        // NOTE: this middleware only handled email verification - no other permission
        // or authentication check is affected.
        // ---------------------------------------------------------------------------
        // $verificationEnabled = (bool) config('auth.email_verification.enabled', true);

        // if ($verificationEnabled && Auth::guard('educator')->check() && Auth::guard('educator')->user()->email_verified_at == null)
        // {
        //     return redirect(RouteServiceProvider::EDUCATOR_VERIFY_EMAIL);
        // }else{
        //     return $next($request);
        // }

        return $next($request);
    }
}
