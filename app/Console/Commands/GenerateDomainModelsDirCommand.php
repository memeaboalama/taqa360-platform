<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use function PHPUnit\Framework\directoryExists;

class GenerateDomainModelsDirCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-domains-models-directories-path';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        
        $provider = $this->getDomainsModelsDirectoriesPath();
        $providerFile = base_path("getDomainsModelsDirectoriesPath.log");
        file_put_contents($providerFile, json_encode($provider));

        // $this->info("Application $name Created Successfully!");
    }


    protected function getDomainsModelsDirectoriesPath(): array
    {
        $namespaces = [];
        $modelsConfig = [];
        $domainsPath = app_path('Domain');
        foreach (array_diff(scandir($domainsPath), ['.', '..', '.DS_Store']) as $domain) {
            $domainPath = app_path("Domain/$domain/Models");
            $parentModel="base_path('app' . DIRECTORY_SEPARATOR . 'Domain' . DIRECTORY_SEPARATOR . '$domain' . DIRECTORY_SEPARATOR . 'Models'),";
            if (file_exists($domainPath)) {
                $namespaces[] = $domainPath;
                $modelsConfig[] = $parentModel;
                foreach (array_diff(scandir($domainPath), ['.', '..', '.DS_Store']) as $subDomain) {
                    $subDomainPath = app_path("Domain/$domain/Models/$subDomain");
                    $subParentModel="base_path('app' . DIRECTORY_SEPARATOR . 'Domain' . DIRECTORY_SEPARATOR . '$domain' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . '$subDomain'),";
                    if (file_exists($subDomainPath) && is_dir($subDomainPath)) {
                        $namespaces[] = $subDomainPath;
                        $modelsConfig[] = $subParentModel;
                    }
                }
            }
        }
        return ['models'=>$modelsConfig,'paths'=>$namespaces];
    }
}
