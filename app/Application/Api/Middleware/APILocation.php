<?php

namespace App\Application\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class APILocation
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


            app()->instance('lat', false);
            app()->instance('long', false);
            // Log::info()
            $auth_user = auth('sanctum')->user();
            $auth_id = $auth_user?->id ?? auth('sanctum')->id();
            if (isset($request->latitude) && isset($request->longitude)) {
                app()->instance('long', $request->longitude ?? false);
                app()->instance('lat', $request->latitude ?? false);

                Log::channel("daily")->info("user id: " . $auth_id . "  request latitude: " . $request->latitude . ", longitude: " . $request->longitude);
                // Log::channel("daily")->debug("request latitude: ".$request->latitude.", longitude: ".$request->longitude);
            } else if (isset($request->address) && isset($request->address->latitude) && isset($request->address->longitude)) {

                app()->instance('long', $request->address->longitude ?? false);
                app()->instance('lat', $request->address->latitude ?? false);

                Log::channel("daily")->info("user id: " . $auth_id . "  request address latitude: " . $request->latitude . ", longitude: " . $request->longitude);
                // Log::channel("daily")->debug("request latitude: ".$request->latitude.", longitude: ".$request->longitude);
            }
            // else if ($request->hasHeader('x-latitude') && $request->hasHeader('x-longitude')) {

            //     app()->instance('long', $request->header('x-longitude') ?? false);
            //     app()->instance('lat', $request->header('x-latitude') ?? false);
            //     Log::channel("daily")->info("user id: ".$auth_id."  request x-latitude: ".$request->header('x-latitude') .", x-longitude: ".$request->header('x-longitude'));
            //     // Log::info("request x-latitude: ".$request->header('x-latitude') .", x-longitude: ".$request->header('x-longitude'));
            // }
            else {

                $address = auth('sanctum')->user()?->defaultAddress;

                if ($address) {
                    app()->instance('long', $address?->longitude ?? false);
                    app()->instance('lat', $address?->latitude ?? false);
                }
            }
        }

        return $next($request);
    }
}
