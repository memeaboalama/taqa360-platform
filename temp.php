<?php
require 'vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Domain\Project\Models\Project;
use Domain\Provider\Models\Provider;

$project = Project::first();
echo "Project ID: " . $project->id . "\n";
echo "Project Title: " . $project->title . "\n";
echo "Provider ID: " . ($project->provider?->id ?? 'N/A') . "\n";
echo "Project UUID: " . $project->uuid . "\n";