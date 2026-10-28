<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class MakeLivewireCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:livewire {name} {app} {appNameSpace} {path?}';

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
        $name = ucfirst($this->argument('name'));
        $app = ucfirst($this->argument('app'));
        $appNameSpace = strtolower($this->argument('appNameSpace'));
        $paths = ucfirst($this->argument('path'));


        if(File::exists(app_path("Application/$app/Livewire/$paths/$name.php"))){
            $this->error("livewire {$name} exists!,please try another name");
        }


        try {
            mkdir(app_path("Application/$app/Livewire"));
        } catch (\Exception $e) {
        }

        $fullPath = null;
        if ($paths) {
            $pathArray = [];
            foreach (explode("/", $paths) as $path) {
                $pathArray[] = $path;

                $fullPath = implode('\\', $pathArray);
                try {
                    mkdir(app_path("Application/$app/Livewire/$fullPath"));
                } catch (\Exception $e) {
                }
            }
        }
        $fullPath = $fullPath ? "\\" . $fullPath : '';

        $livewireStub = resource_path('stubs/livewire.stub');
        $livewire = file_get_contents($livewireStub);


        $livewire = str_replace('{{ name }}', ucfirst($name), $livewire);
        $livewire = str_replace('{{ namespace }}', "Application\\$app\\Livewire$fullPath", $livewire);
        $viewPath = str_replace('\\', ".", $fullPath);
        $viewPath = strtolower($viewPath);
        $viewName = strtolower($name);
        $livewire = str_replace('{{ view }}', "$appNameSpace::livewire$viewPath.$viewName", $livewire);
        $modelFile = app_path("Application\\$app\\Livewire/$fullPath\\$name.php");


        file_put_contents($modelFile, $livewire);
    }
}
