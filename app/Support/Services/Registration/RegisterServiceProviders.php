<?php

namespace App\Support\Services\Registration;

class RegisterServiceProviders
{
    /**
     * @return array
     */
    public static function getServiceProviders(): array
    {
        $namespaces = [];
        $appsPath = app_path('Application');
        foreach (array_diff(scandir($appsPath), ['.', '..', '.DS_Store']) as $app) {
            $providers = str_replace('.php', '', array_diff(scandir(app_path("Application/$app/Providers")), ['.', '..', '.DS_Store']));
            foreach ($providers as $provider) {
                $namespaces[] = (new \ReflectionClass("\\App\\Application\\$app\\Providers\\$provider"))->getName();
            }
        }
        return $namespaces;
    }
}
