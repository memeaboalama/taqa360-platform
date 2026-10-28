<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:controller {name} {app} {name_space} {dir?}';
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
        $app = ucfirst($this->argument('app'));
        $nameSpace = ucfirst($this->argument('name_space'));

        $stubPath = resource_path('stubs/controller.stub');
        $dir = ucfirst($this->argument('dir')) ? DIRECTORY_SEPARATOR . ucfirst($this->argument('dir')) : null;

        try {
            mkdir(app_path("Application/$app/Controllers$dir"), 777);
        } catch (\Exception $e) {

        }
        $controller = file_get_contents($stubPath);
        $controller = str_replace('{{ appName }}', ucfirst($app), $controller);
        $controller = str_replace('{{ nameSpace }}', strtolower($nameSpace), $controller);
        $controller = str_replace('{{ dir }}', ucfirst($dir), $controller);
        $controller = str_replace('{{ className }}', $name, $controller);

        $controllerFile = app_path("Application/$app/Controllers$dir/$name.php");
        if (file_exists($controllerFile)) {
            $this->error("The {$name} is exists , please try another name.");
            return;
        }
        file_put_contents($controllerFile, $controller);

        $this->info("The {$name} created successfully");
    }
}
