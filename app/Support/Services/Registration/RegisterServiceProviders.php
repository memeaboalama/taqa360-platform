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
            $providersPath = app_path("Application/$app/Providers");

            if (!is_dir($providersPath)) {
                continue;
            }

            foreach (array_diff(scandir($providersPath), ['.', '..', '.DS_Store']) as $providerFile) {
                $providerPath = $providersPath . DIRECTORY_SEPARATOR . $providerFile;

                if (!is_file($providerPath) || pathinfo($providerPath, PATHINFO_EXTENSION) !== 'php') {
                    continue;
                }

                $provider = pathinfo($providerFile, PATHINFO_FILENAME);
                $providerClass = "\\App\\Application\\$app\\Providers\\$provider";

                if (class_exists($providerClass)) {
                    $namespaces[] = (new \ReflectionClass($providerClass))->getName();
                }
            }
        }
        return $namespaces;
    }
}
