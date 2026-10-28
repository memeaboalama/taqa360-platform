<?php
// test_device_tokens.php
require 'vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Device Tokens Database Analysis ===\n\n";

// 1. Total count
$total = \Domain\Notification\Models\DeviceToken::count();
echo "1. Total device tokens in DB: $total\n\n";

// 2. By deviceable_type
echo "2. Tokens by deviceable_type:\n";
$tokenTypes = \Domain\Notification\Models\DeviceToken::select("deviceable_type")
    ->distinct()
    ->pluck("deviceable_type")
    ->toArray();

foreach ($tokenTypes as $type) {
    $count = \Domain\Notification\Models\DeviceToken::where("deviceable_type", $type)->count();
    echo "  - $type: $count tokens\n";
}

echo "\n";

// 3. Check table columns using DB facade
echo "3. Device_tokens table columns:\n";
try {
    $columns = \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing("device_tokens");
    echo "  " . implode(", ", $columns) . "\n\n";
} catch (\Exception $e) {
    echo "  Could not fetch columns: " . $e->getMessage() . "\n\n";
}

// 4. Check sample counts per category
echo "4. Token counts per category:\n";

$categories = [
    "Users" => "App\\Domain\\User\\Models\\User",
    "Drivers" => "Domain\\Driver\\Models\\Driver", 
    "MerchantEmployees" => "Domain\\MarketPlace\\Models\\MerchantEmployee",
    "Educators" => "Domain\\Educator\\Models\\Educator",
    "SalesAgents" => "App\\Domain\\SalesAgent\\Models\\SalesAgent",
];

foreach ($categories as $name => $class) {
    $count = \Domain\Notification\Models\DeviceToken::where("deviceable_type", $class)->count();
    echo "  - $name: $count tokens\n";
}