<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:migration {name} {domain}';

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
        $domain = ucfirst($this->argument('domain'));

        $stubPath = resource_path('stubs/migrations.stub');
        $migration = file_get_contents($stubPath);
        $migName = date('Y_m_d_His') . "_create_".strtolower($name . 's')."_table";
        $migration = str_replace('{{ name }}', strtolower($name . 's'), $migration);
        $migrationFile = app_path("Domain".DIRECTORY_SEPARATOR."$domain".DIRECTORY_SEPARATOR."Migrations".DIRECTORY_SEPARATOR."$migName.php");
        file_put_contents($migrationFile, $migration);
    }
}
