<?php
require 'vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domain\Provider\Models\Provider;
use Domain\Project\Models\Project;

echo "=== Provider ID 1 ===\n";
$provider = Provider::find(1);
if ($provider) {
    echo "ID: " . $provider->id . "\n";
    echo "Name: " . $provider->name . "\n";
    echo "Email: " . ($provider->email ?? 'null') . "\n";
    echo "Company ID: " . ($provider->company_id ?? 'null') . "\n";
    
    // Check projects related to this provider
    echo "\n=== Projects with Provider ID 1 ===\n";
    $projects = Project::whereHas('provider', function($q) use ($provider) {
        $q->where('provider_id', $provider->id);
    })->get();
    foreach ($projects as $proj) {
        echo "Project ID: " . $proj->id . " | Title: " . ($proj->title ?? 'N/A') . "\n";
    }
} else {
    echo "Provider not found!\n";
}