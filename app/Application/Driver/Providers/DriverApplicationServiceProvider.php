<?php

namespace App\Application\Driver\Providers;


use File;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Route;

class DriverApplicationServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(app_path('Application/Driver/resources/views'), 'driver');
        // lang
        $this->loadTranslationsFrom(app_path('Application/Driver/lang'), 'driver');

        $this->registerLivewireComponents(app_path('Application/Driver/Livewire'));

    }


    private function registerLivewireComponents($directory): void
    {
        $files = \File::allFiles($directory);
        foreach ($files as $file) {
            try {
                $relativePath = $file->getRelativePath();
                $fileName = $file->getFilenameWithoutExtension();

                $componentName = ($relativePath ? $relativePath . '.' : '') . $fileName;
                $className = 'Application\\Driver\\Livewire\\' . str_replace('/', '\\', $relativePath) . '\\' . $fileName;
                $componentName = strtolower('driver::' . str_replace("/", '.', $componentName));
                $componentName = strtolower(str_replace("\\", '.', $componentName));

                Livewire::component($componentName, $className);
            } catch (\Exception $e) {

            }
        }
    }

    /**
     * Define the routes for the application.
     */


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
