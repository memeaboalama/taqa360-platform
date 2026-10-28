<?php
// test_notif_check.php - Check notification data
require 'vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Check model table
$model = new \App\Domain\AppBase\Models\NotificationNew();
echo "Model table: " . $model->getTable() . "\n";

// 2. Check actual table schema
$columns = \Illuminate\Support\Facades\DB::getSchemaBuilder()->getColumnListing($model->getTable());
echo "Table columns: " . implode(", ", $columns) . "\n";

// 3. Check record count
$count = \App\Domain\AppBase\Models\NotificationNew::count();
echo "Record count: $count\n";

// 4. Check with admin_id filter
$countAdmin1 = \App\Domain\AppBase\Models\NotificationNew::where('admin_id', 1)->count();
echo "Records for admin_id=1: $countAdmin1\n";

$countAdmin3 = \App\Domain\AppBase\Models\NotificationNew::where('admin_id', 3)->count();
echo "Records for admin_id=3: $countAdmin3\n";

// 5. Show all records
echo "\nAll records:\n";
$notifications = \App\Domain\AppBase\Models\NotificationNew::all();
foreach ($notifications as $n) {
    echo "ID: " . $n->id 
         . ", Title: " . $n->title 
         . ", admin_id: " . $n->admin_id 
         . ", created_at: " . $n->created_at 
         . "\n";
}