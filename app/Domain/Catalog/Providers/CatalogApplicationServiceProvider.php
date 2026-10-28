<?php

namespace App\Domain\Catalog\Providers;


use File;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
// use Route;
use Illuminate\Support\Facades\Route;

class CatalogApplicationServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(app_path('Domain/Catalog/views'), 'catalog');
        // lang
        // $this->loadTranslationsFrom(app_path('Application/Marketplace/lang'), 'marketplace');


    }


    

    /**
     * Define the routes for the application.
     */


    protected function registerRoutes(): void
    {
        // Route::prefix('api')->middleware('api')->namespace('Application\*\Controllers')->group(function () {
        //     require app_path('Application/Api/routes/api.php');
        // });
        // // \Route::middleware('web')
        // // \Route::middleware('session.tenant:marketplace')
        // //     ->group(function () {
        // //         $this->loadRoutesFrom(
        // //             path: __DIR__ . '/../routes/web.php',
        // //         );
        // //     });
        // Route::middleware('web')
        //     ->group(function () {
                
        //         Route::middleware('session.tenant:marketplace')->group(function () {
        //             $this->loadRoutesFrom(
        //                 path: __DIR__ . '/../routes/web.php',
        //             );
        //         });
        //     });
        // \Route::group(function () {
        //         $this->loadRoutesFrom(
        //             path: __DIR__ . '/../routes/web.php',
        //         );
        //     });

        Route::prefix('api')
            ->middleware('api')
            ->group(function () {
                $this->loadRoutesFrom(
                    path: __DIR__ . '/../routes/api.php',
                );
            });
    }

}
