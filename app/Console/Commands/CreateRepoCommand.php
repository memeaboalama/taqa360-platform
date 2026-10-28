<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateRepoCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repo {name} {domain} {dir?}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a new repository class';


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $className = $this->argument('name');
        $dir = $this->argument('dir');
        $domain = $this->argument('domain');
        $fileName = app_path("Domain/$domain/Repositories$dir/{$className}.php");
        $stubPath = resource_path('stubs/repo.stub');

        try {
            mkdir(app_path("Domain/$domain/Repositories$dir"));
        } catch (\Exception $e) {
        }

        if (file_exists($fileName)) {
            $this->error("{$className} repository exists!,please try another name");
            return;
        }

        $stub = file_get_contents($stubPath);
        $path = $dir ? "Repositories\${$dir}".DIRECTORY_SEPARATOR : "Repositories";
        $stub = str_replace('{{ class }}', $className, $stub);
        $stub = str_replace('{{ namespace }}', $path, $stub);
        $stub = str_replace('{{ domain }}', ucfirst($domain), $stub);
        file_put_contents($fileName, $stub);

        $this->info("{$className} repository generated successfully!");


    }
}
