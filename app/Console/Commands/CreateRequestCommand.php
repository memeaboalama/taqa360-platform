<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:request {name} {app} {path}';

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
        $path = ucfirst($this->argument('path'));

        try {
            mkdir(app_path("Application/$app/Requests"));

        } catch (\Exception $e) {
        }
        try {
            mkdir(app_path("Application/$app/Requests/$path"));
        } catch (\Exception $e) {
        }
        $fileName = app_path("Application/$app/Requests/$path/$name.php");
        $stubPath = resource_path('stubs/request.stub');
        $request = $stub = file_get_contents($stubPath);

        $request = str_replace('{{ app }}', $app, $request);
        $request = str_replace('{{ class }}', $name, $request);
        $path = $path ? DIRECTORY_SEPARATOR."$path" : null;
        $request = str_replace('{{ path }}', $path, $request);
        file_put_contents($fileName, $request);

        $this->info("{$name} request generated successfully!");

    }
}
