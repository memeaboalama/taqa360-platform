<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Traits\LoadsTranslatedCachedRoutes;

class RouteServiceProvider extends ServiceProvider
{
    use LoadsTranslatedCachedRoutes;
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';
    public const MARKETPLACE = 'merchant';
    public const MARKETPLACE_HOME = RouteServiceProvider::MARKETPLACE . '/home';
    public const MARKETPLACE_LOGIN = RouteServiceProvider::MARKETPLACE . '/login';
    public const MARKETPLACE_VERIFY_EMAIL = RouteServiceProvider::MARKETPLACE . '/verify-email';

    public const ADMIN_LOGIN = 'admin/login';
    public const ADMIN_HOME = 'admin/home';
    public const ADMIN_DRIVERS_REGISTER = 'admin/drivers/register';

    public const COMPANY = 'company';
    public const COMPANY_HOME = RouteServiceProvider::COMPANY . '/home';
    public const COMPANY_LOGIN = RouteServiceProvider::COMPANY . '/login';


    public const DRIVER = 'driver';
    public const DRIVER_HOME = RouteServiceProvider::DRIVER . '/home';
    public const DRIVER_LOGIN = RouteServiceProvider::DRIVER . '/login';
    public const DRIVER_REGISTER = RouteServiceProvider::DRIVER . '/register';
    public const DRIVER_WELCOME = RouteServiceProvider::DRIVER . '/welcome';
    public const DRIVER_FILE = RouteServiceProvider::DRIVER . '/file';


    public const EDUCATOR = 'educator';
    public const EDUCATOR_HOME = RouteServiceProvider::EDUCATOR . '/home';
    public const EDUCATOR_LOGIN = RouteServiceProvider::EDUCATOR . '/login';
    public const EDUCATOR_REGISTER = RouteServiceProvider::EDUCATOR . '/register';
    public const EDUCATOR_WELCOME = RouteServiceProvider::EDUCATOR . '/welcome';
    public const EDUCATOR_FILE = RouteServiceProvider::EDUCATOR . '/file';
    public const EDUCATOR_VERIFY_EMAIL = RouteServiceProvider::EDUCATOR . '/verify';

    public const ECOMMERCE = 'ecommerce';
    public const ECOMMERCE_HOME = RouteServiceProvider::ECOMMERCE . '/home';
    public const ECOMMERCE_LOGIN = RouteServiceProvider::ECOMMERCE . '/login';
    public const ECOMMERCE_REGISTER = RouteServiceProvider::ECOMMERCE . '/register';
    public const ECOMMERCE_WELCOME = RouteServiceProvider::ECOMMERCE . '/welcome';
    public const ECOMMERCE_FILE = RouteServiceProvider::ECOMMERCE . '/file';
    public const ECOMMERCE_VERIFY_EMAIL = RouteServiceProvider::ECOMMERCE . '/verify';


    public static function driverFileRoute($id)
    {
        return route('driver.file', ['id' => $id]);
    }

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
