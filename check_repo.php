<?php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Domain\MarketPlace\Repositories\Merchant\MerchantEmployeeRepository;

$repo = app(MerchantEmployeeRepository::class);

$sql = $repo->builder()
    ->where('merchant_id', 45)
    ->where('id', '!=', 83)
    ->toSql();
echo 'SQL: ' . $sql . PHP_EOL;

$result = $repo->builder()
    ->where('merchant_id', 45)
    ->where('id', '!=', 83)
    ->get();
echo 'count: ' . $result->count() . PHP_EOL;
foreach ($result as $e) {
    echo "  - id={$e->id} name={$e->name} status={$e->status}\n";
}