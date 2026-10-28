<?php
require 'vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domain\Provider\Models\Provider;
use App\Domain\Project\Models\Project;

echo "=== Providers ===\n";
$providers = Provider::all();
foreach ($providers as $p) {
    echo "Provider ID: " . $p->id . " | Name: " . $p->name . " | Email: " . ($p->email ?? 'null') . "\n";
    $projects = $p->projects;
    echo "  Projects count: " . $projects->count() . "\n";
    foreach ($projects as $proj) {
        echo "    - Project ID: " . $proj->id . " | Title: " . ($proj->title ?? 'N/A') . "\n";
    }
}

echo "\n=== All Projects ===\n";
$allProjects = Project::all();
foreach ($allProjects as $proj) {
    echo "Project ID: " . $proj->id . " | Title: " . ($proj->title ?? 'N/A') . " | Provider ID: " . ($proj->provider?->id ?? 'N/A') . "\n";
}