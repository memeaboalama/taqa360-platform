<?php

namespace App\Application\Admin\Providers;


use File;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes;
use Route;

class AdminApplicationServiceProvider extends ServiceProvider
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
        $this->registerRoutes();
        $this->loadViewsFrom(app_path('Application/Admin/resources/views'), 'admin');
        $this->loadTranslationsFrom(app_path('Application/Admin/lang'), 'admin');
        $this->registerLivewireComponents(app_path('Application/Admin/Livewire'));
    }


    private function registerLivewireComponents($directory): void
    {
        $files = \File::allFiles($directory);
        foreach ($files as $file) {
            try {
                $relativePath = str_replace('\\', '/', $file->getRelativePath());
                $fileName = $file->getFilenameWithoutExtension();

                $className = 'Application\\Admin\\Livewire\\' . str_replace('/', '\\', $relativePath) . '\\' . $fileName;

                $pathParts = explode('/', $relativePath);
                $kebabPath = implode('.', array_map(function($part) {
                    return strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $part));
                }, $pathParts));

                $kebabFileName = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0', $fileName));

                $componentName = 'admin::' . ($kebabPath ? $kebabPath . '.' : '') . $kebabFileName;

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
