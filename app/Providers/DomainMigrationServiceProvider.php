<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DomainMigrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $domainsJson = file_get_contents(app_path('Domain/order.json'));

        $file = json_decode($domainsJson);
        foreach ($file->domains as $domain) {
            $migrationPath = app_path("Domain/{$domain}/Migrations");
            if (is_dir($migrationPath)) {
                $this->loadMigrationsFrom($migrationPath);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
