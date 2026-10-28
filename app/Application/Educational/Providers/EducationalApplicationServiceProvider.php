<?php

namespace App\Application\Educational\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

class EducationalApplicationServiceProvider extends ServiceProvider
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
        $this->registerRoutes();
        // Route::prefix('application')->namespace('Application\Educational\Controllers')->group(function () {
        //     require app_path('Application/Educational/routes/web.php');
        // });
        // views
        $this->loadViewsFrom(app_path('Application/Educational/resources/views'), 'application');
        // lang
        $this->loadTranslationsFrom(app_path('Application/Educational/lang'), 'application');

    }

    protected function registerRoutes(): void
    {
        Route::middleware('web')
            ->group(function () {
                $this->loadRoutesFrom(
                    path: __DIR__ . '/../routes/web.php',
                );
            });

        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                $this->loadRoutesFrom(
                    path: __DIR__ . '/../routes/api.php',
                );
            });
    }
}
