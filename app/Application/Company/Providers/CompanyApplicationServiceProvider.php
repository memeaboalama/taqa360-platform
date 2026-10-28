<?php

namespace App\Application\Company\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Route;

class CompanyApplicationServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(app_path('Application/Company/resources/views'), 'company');
        // lang
        $this->loadTranslationsFrom(app_path('Application/Company/lang'), 'company');

        $this->registerLivewireComponents(app_path('Application/Company/Livewire'));

    }


    private function registerLivewireComponents($directory): void
    {
        $files = \File::allFiles($directory);
        foreach ($files as $file) {
            try {
                $relativePath = $file->getRelativePath();
                $fileName = $file->getFilenameWithoutExtension();

                $componentName = ($relativePath ? $relativePath . '.' : '') . $fileName;
                $className = 'Application\\Company\\Livewire\\' . str_replace('/', '\\', $relativePath) . '\\' . $fileName;
                $componentName = strtolower('company::' . str_replace("/", '.', $componentName));
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
    }
}
