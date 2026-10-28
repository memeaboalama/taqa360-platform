<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateApplicationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:app {name} {name_space}';

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
        $name = $this->argument('name');
        $nameSpace = $this->argument('name_space');
        $createApplicationDir = app_path("Application/$name");
        try {
            mkdir(app_path("Application/$name"), 777);
            chmod(app_path("Application/$name"), 0777);

        } catch (\Exception $e) {
            $this->error("{$name} application exists!,please try another name");
            return;
        }

        mkdir(app_path("Application/$name/Controllers"), 777);
        mkdir(app_path("Application/$name/lang"), 777);
        chmod(app_path("Application/$name/lang"), 0777);
        mkdir(app_path("Application/$name/lang/en"), 777);
        mkdir(app_path("Application/$name/lang/ar"), 777);
        mkdir(app_path("Application/$name/resources"), 777);
        chmod(app_path("Application/$name/resources"), 0777);
        mkdir(app_path("Application/$name/resources/views"), 777);
        mkdir(app_path("Application/$name/routes"), 777);
        mkdir(app_path("Application/$name/Providers"), 777);
        mkdir(app_path("Application/$name/requests"), 777);
        mkdir(app_path("Application/$name/middleware"), 777);
        chmod(app_path("Application/$name/Providers/"), 0777);
        chmod(app_path("Application/$name/Controllers/"), 0777);
        chmod(app_path("Application/$name/routes/"), 0777);
        chmod(app_path("Application/$name/requests/"), 0777);
        chmod(app_path("Application/$name/middleware/"), 0777);
        chmod(app_path("Application/$name/resources/views"), 0777);


        //  generate controller

        $className = ucfirst($name) . "Controller";

        \Artisan::call("make:controller " . $className.' ' . $name . " $nameSpace");


        //  generate route
        $stubPath = resource_path('stubs/route.stub');
        $route = file_get_contents($stubPath);
        $route = str_replace('{{ Name }}', $name, $route);
        $route = str_replace('{{ controllerName }}', $className, $route);
        $routeFile = app_path("Application/$name/routes/web.php");
        file_put_contents($routeFile, $route);


        //  generate view
        $stubPath = resource_path('stubs/view.stub');
        $view = file_get_contents($stubPath);
        $view = str_replace('{{ Name }}', $name, $view);
        $viewFile = app_path("Application/$name/resources/views/welcome.blade.php");
        file_put_contents($viewFile, $view);

        //  generate provider
        $stubPath = resource_path('stubs/provider.stub');
        $provider = file_get_contents($stubPath);
        $provider = str_replace('{{ appName }}', $name, $provider);
        $proName = ucfirst($name) . "ApplicationServiceProvider";
        $provider = str_replace('{{ className }}', $proName, $provider);
        $provider = str_replace('{{ name_space }}', strtolower($nameSpace), $provider);
        $providerFile = app_path("Application/$name/Providers/$proName.php");
        file_put_contents($providerFile, $provider);

        $this->info("Application $name Created Successfully!");
    }
}
