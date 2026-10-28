<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$userModel = new \App\Domain\User\Models\User();
echo "Table: " . $userModel->getTable() . "\n";
echo "Connection: " . $userModel->getConnectionName() . "\n";

// Check if users table exists and has data
$count = \Illuminate\Support\Facades\DB::table($userModel->getTable())->count();
echo "User count: " . $count . "\n";

if ($count > 0) {
    $user = \Illuminate\Support\Facades\DB::table($userModel->getTable())->first();
    echo "First user: " . json_encode($user) . "\n";
}