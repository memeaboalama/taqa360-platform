<?php

namespace App\Providers;

use App\Support\Services\Registration\RegisterServiceProviders;
use App\Websocket\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        foreach (RegisterServiceProviders::getServiceProviders() as $provider) $this->app->register($provider);
        $this->app->register(\App\Domain\Catalog\Providers\CatalogApplicationServiceProvider::class);
        $this->app->singleton('websockets.router', function () {
            return new Router();
        });

        Blade::if('MerchantHasRole', function ($permissions = []) {
            if (Auth::guard('merchant')->check()) setPermissionsTeamId(Auth::guard('merchant')->user()->id);
            if (Auth::guard('merchant_employee')->check()) setPermissionsTeamId(Auth::guard('merchant_employee')->user()->merchant_id);

            if (Auth::guard('merchant')->check()) {
                return true;

            } elseif (Auth::guard('merchant_employee')->check() && Auth::guard('merchant_employee')->user()->hasAnyPermission($permissions)) {
                return true;
            }

            return false;
        });

        Blade::if('AdminHasRole', function ($role = []) {
            setPermissionsTeamId(1);
            if (Auth::guard('admin')->user()?->type == 'employee' && Auth::guard('admin')->user()->hasAnyPermission($role)) {
                return true;
            } elseif (Auth::guard('admin')->user()?->type == 'admin' && Auth::guard('admin')->user()) {
                return true;
            }
            return false;
        });

        Blade::if('CompanyHasRole', function ($permissions = []) {
            if (Auth::guard('company')->check()) setPermissionsTeamId(Auth::guard('company')->user()->id);
            if (Auth::guard('company_employee')->check()) setPermissionsTeamId(Auth::guard('company_employee')->user()->merchant_id);

            if (Auth::guard('company')->check()) {
                return true;

            } elseif (Auth::guard('company_employee')->check() && Auth::guard('company_employee')->user()->hasAnyPermission($permissions)) {
                return true;
            }

            return false;
        });

        Blade::if('DriverHasRole', function ($permissions = []) {
            $driver = Auth::guard('driver')->user();
            if (!$driver) {
                return false;
            }
            if (method_exists($driver, 'hasAnyPermission')) {
                return $driver->hasAnyPermission($permissions);
            }
            return false;
        });


        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
         // Schema::defaultStringLength(1000);

        if ($this->app->isLocal()) {
            //if local register your services you require for development
                $this->app->register('Barryvdh\Debugbar\ServiceProvider');
            } else {
            //else register your services you require for production
                $this->app['request']->server->set('HTTPS', true);
            }

        // Ensure wkhtmltopdf binary has execute permissions on Linux
        if (PHP_OS_FAMILY === 'Linux') {
            $pdfBinary = base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
            $imgBinary = base_path('vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64');
            if (file_exists($pdfBinary) && !is_executable($pdfBinary)) {
                chmod($pdfBinary, 0755);
            }
            if (file_exists($imgBinary) && !is_executable($imgBinary)) {
                chmod($imgBinary, 0755);
            }
        }

        // Register Livewire Components for Banner Management
        $this->registerLivewireComponents();
    }

    /**
     * Register Livewire Components
     */
    protected function registerLivewireComponents(): void
    {
        // Banner Spaces Components
        \Livewire\Livewire::component('admin::banner-space.page-selector', \Application\Admin\Livewire\BannerSpace\PageSelector::class);
        \Livewire\Livewire::component('admin::banner-space.space-cards', \Application\Admin\Livewire\BannerSpace\SpaceCards::class);
        \Livewire\Livewire::component('admin::banner-space.modal.create-space', \Application\Admin\Livewire\BannerSpace\Modal\CreateSpace::class);
        \Livewire\Livewire::component('admin::banner-space.modal.delete-space', \Application\Admin\Livewire\BannerSpace\Modal\DeleteSpace::class);
        \Livewire\Livewire::component('admin::banner-space.modal.update-space', \Application\Admin\Livewire\BannerSpace\Modal\UpdateSpace::class);

        // Ads Content Components
        \Livewire\Livewire::component('admin::ads-content.page-selector', \Application\Admin\Livewire\AdsContent\PageSelector::class);
        \Livewire\Livewire::component('admin::ads-content.placement-cards', \Application\Admin\Livewire\AdsContent\PlacementCards::class);
        \Livewire\Livewire::component('admin::ads-content.content-table', \Application\Admin\Livewire\AdsContent\ContentTable::class);
        \Livewire\Livewire::component('admin::ads-content.history-table', \Application\Admin\Livewire\AdsContent\HistoryTable::class);
        \Livewire\Livewire::component('admin::ads-content.modal.create-content', \Application\Admin\Livewire\AdsContent\Modal\CreateContent::class);
        \Livewire\Livewire::component('admin::ads-content.modal.delete-content', \Application\Admin\Livewire\AdsContent\Modal\DeleteContent::class);
        \Livewire\Livewire::component('admin::ads-content.modal.update-content', \Application\Admin\Livewire\AdsContent\Modal\UpdateContent::class);
        \Livewire\Livewire::component('admin::ads-content.modal.reactivate', \Application\Admin\Livewire\AdsContent\Modal\Reactivate::class);
        \Livewire\Livewire::component('admin::ads-content.shared.live-search', \Application\Admin\Livewire\AdsContent\Shared\LiveSearch::class);
        \Livewire\Livewire::component('admin::ads-content.header-settings', \Application\Admin\Livewire\AdsContent\HeaderSettings::class);

        // Ad Placement Components
        \Livewire\Livewire::component('admin::adplacement.space-cards', \Application\Admin\Livewire\AdPlacement\SpaceCards::class);
        \Livewire\Livewire::component('admin::adplacement.modal.create', \Application\Admin\Livewire\AdPlacement\Modal\Create::class);
        \Livewire\Livewire::component('admin::adplacement.modal.update', \Application\Admin\Livewire\AdPlacement\Modal\Update::class);
        \Livewire\Livewire::component('admin::adplacement.modal.delete', \Application\Admin\Livewire\AdPlacement\Modal\Delete::class);
    }
}

