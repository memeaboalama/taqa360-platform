<?php

namespace App\Application\Ecommerce\Providers;

use Illuminate\Support\ServiceProvider;
use Route;

class EcommerceApplicationServiceProvider extends ServiceProvider
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
        // views
        $this->loadViewsFrom(app_path('Application/Ecommerce/resources/views'), 'ecommerce');
        // lang
        $this->loadTranslationsFrom(app_path('Application/Ecommerce/lang'), 'ecommerce');

        // View Composers
        \Illuminate\Support\Facades\View::composer(
            ['ecommerce::layout.header', 'ecommerce::layout.layout'],
            \App\Http\View\Composers\HeaderComposer::class
        );

    }


    protected function registerRoutes(): void
    {
        \Route::middleware('web')
            ->group(function () {
                $this->loadRoutesFrom(
                    path: __DIR__ . '/../routes/web.php',
                );
            });

        \Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                $this->loadRoutesFrom(
                    path: __DIR__ . '/../routes/api.php',
                );
            });
    }
}
