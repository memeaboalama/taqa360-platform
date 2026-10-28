<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:model {name} {domainName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = ucfirst($this->argument('name'));
        $domain = ucfirst($this->argument('domainName'));
        $stubPath = resource_path('stubs/model.stub');
        $model = file_get_contents($stubPath);
        $model = str_replace('{{ appName }}', ucfirst($name), $model);
        $model = str_replace('{{ namespace }}', "Domain".DIRECTORY_SEPARATOR."$domain".DIRECTORY_SEPARATOR."Models", $model);
        $model = str_replace('{{ class }}', $name, $model);
        $modelFile = app_path("Domain".DIRECTORY_SEPARATOR."$domain".DIRECTORY_SEPARATOR."Models".DIRECTORY_SEPARATOR."$name.php");

        file_put_contents($modelFile, $model);

        $this->info("Model $name Created Successfully!");

    }
}
