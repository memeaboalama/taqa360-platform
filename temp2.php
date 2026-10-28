<?php
require 'vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domain\Provider\Models\Provider;

$providers = Provider::all();
echo "Total Providers: " . $providers->count() . "\n";
foreach ($providers as $p) {
    echo "Provider ID: " . $p->id . " | Name: " . $p->name . " | Email: " . $p->email . "\n";
    $projects = $p->projects;
    echo "  Projects count: " . $projects->count() . "\n";
    foreach ($projects as $proj) {
        echo "    - Project: " . $proj->title . " (ID: " . $proj->id . ")\n";
    }
}