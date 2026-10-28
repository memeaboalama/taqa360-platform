<?php

namespace App\Application\Educator\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Route;

class EducatorApplicationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {

    }

    public function boot(): void
    {
        // routes
        $this->registerRoutes();
        // views
        $this->loadViewsFrom(app_path('Application/Educator/resources/views'), 'educator');
        // lang
        $this->loadTranslationsFrom(app_path('Application/Educator/lang'), 'educator');

        $this->registerLivewireComponents(app_path('Application/Educator/Livewire'));

    }

    private function registerLivewireComponents($directory): void
    {
        $files = \File::allFiles($directory);
        foreach ($files as $file) {
            try {
                $relativePath = $file->getRelativePath();
                $fileName = $file->getFilenameWithoutExtension();

                $componentName = ($relativePath ? $relativePath . '.' : '') . $fileName;
                $className = 'Application\\Educator\\Livewire\\' . str_replace('/', '\\', $relativePath) . '\\' . $fileName;
                $componentName = strtolower('educator::' . str_replace("/", '.', $componentName));
                $componentName = strtolower(str_replace("\\", '.', $componentName));

                Livewire::component($componentName, $className);
            } catch (\Exception $e) {

            }
        }
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
