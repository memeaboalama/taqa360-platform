<?php
// check_query.php
require 'vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$repo = new \App\Domain\AppBase\Repositories\NotificationRepository(
    new \App\Domain\AppBase\Models\NotificationNew()
);

$query = $repo->builder(order: "desc");
$results = $query->get();
echo "Query results count: " . count($results) . "\n";
foreach ($results as $r) {
    echo " - ID: " . $r->id . ", Title: " . $r->title . ", admin_id: " . $r->admin_id . "\n";
}