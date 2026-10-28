<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateDomainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain {name}';

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
        try {
            mkdir(app_path("Domain/$name"), 777);
            chmod(app_path("Domain/$name"), 0777);
        } catch (\Exception $e) {
            $this->error("{$name} domain exists!,please try another name");
            return;
        }

        //  create directories
        mkdir(app_path("Domain/$name/Actions"), 777);
        chmod(app_path("Domain/$name/Actions"), 0777);

        mkdir(app_path("Domain/$name/Events"), 777);
        chmod(app_path("Domain/$name/Events"), 0777);

        mkdir(app_path("Domain/$name/Migrations"), 777);
        chmod(app_path("Domain/$name/Migrations"), 0777);

        mkdir(app_path("Domain/$name/Models"), 777);
        chmod(app_path("Domain/$name/Models"), 0777);

        mkdir(app_path("Domain/$name/Repositories"), 777);
        chmod(app_path("Domain/$name/Repositories"), 0777);


        //  generate model
        \Artisan::call("make:model " . $name . ' ' . $name);

        //  generate migration
        \Artisan::call("make:migration " . $name . ' ' . $name);


        //  generate Repository
        \Artisan::call('make:repo ' . $name . 'Repository ' . $name);


        $orderDomainFile = app_path('Domain/order.json');
        $domainsJson = file_get_contents($orderDomainFile);
        $data = json_decode($domainsJson);
        $data->domains[] = $name;
        file_put_contents($orderDomainFile, json_encode($data));
        $this->info("Domain $name Created Successfully!");
    }
}
