<?php

namespace App\Application\Api\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

class ApiApplicationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // routes
        Route::prefix('api')->middleware('api')->namespace('Application\*\Controllers')->group(function () {
            require app_path('Application/Api/routes/api.php');
        });
        // views
        $this->loadViewsFrom(app_path('Application/Api/resources/views'), 'api');
        // lang
        $this->loadTranslationsFrom(app_path('Application/Api/lang'), 'api');

    }
}
