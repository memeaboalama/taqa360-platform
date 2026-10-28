<?php

namespace App\Application\Project\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

class ProjectApplicationServiceProvider extends ServiceProvider
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
        // // routes
        $this->registerRoutes();
        // \Route::prefix('application')->namespace('Application\Project\Controllers')->group(function () {
        //     require app_path('Application/Project/routes/web.php');
        // });
        // views
        $this->loadViewsFrom(app_path('Application/Project/resources/views'), 'application');
        // lang
        $this->loadTranslationsFrom(app_path('Application/Project/lang'), 'application');

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
