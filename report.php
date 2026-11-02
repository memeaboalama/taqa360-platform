<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=taqa360_solutions_platform","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function queryCount($pdo,$sql){
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? ($row['cnt'] ?? $row[0] ?? 0) : 0;
}

function queryRow($pdo,$sql){
    return $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
}

echo "=== Countries ===\n";
echo "Total countries: " . queryCount($pdo,"SELECT COUNT(*) AS cnt FROM countries") . "\n";

echo "\n=== Regions ===\n";
echo "Total regions: " . queryCount($pdo,"SELECT COUNT(*) AS cnt FROM regions") . "\n";

echo "\n=== Cities ===\n";
echo "Total cities: " . queryCount($pdo,"SELECT COUNT(*) AS cnt FROM cities") . "\n";

echo "\n=== Type services ===\n";
echo "Total type_services: " . queryCount($pdo,"SELECT COUNT(*) AS cnt FROM type_services") . "\n";

echo "\n=== Packages ===\n";
echo "Total packages: " . queryCount($pdo,"SELECT COUNT(*) AS cnt FROM packages") . "\n";

echo "\n=== city_type_service_packages ===\n";
echo "Total city_type_service_packages: " . queryCount($pdo,"SELECT COUNT(*) AS cnt FROM city_type_service_packages") . "\n";

echo "\n=== Active/Inactive in type_services ===\n";
$rows = $pdo->query("SELECT status, COUNT(*) AS cnt FROM type_services GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);
echo "status breakdown:\n";
foreach($rows as $r){ echo " - {$r['status']}: {$r['cnt']}\n"; }

echo "\n=== Active/Inactive in packages ===\n";
$rows2 = $pdo->query("SELECT status, COUNT(*) AS cnt FROM packages GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);
echo "status breakdown:\n";
foreach($rows2 as $r){ echo " - {$r['status']}: {$r['cnt']}\n"; }

echo "\n=== Active/Inactive in city_type_service_packages ===\n";
$rows3 = $pdo->query("SELECT status, COUNT(*) AS cnt FROM city_type_service_packages GROUP BY status")->fetchAll(PDO::FETCH_ASSOC);
echo "status breakdown:\n";
foreach($rows3 as $r){ echo " - {$r['status']}: {$r['cnt']}\n"; }

echo "\n=== Branches 365 & 366 type_service links ===\n";
$b365 = queryCount($pdo,"SELECT COUNT(*) AS cnt FROM merchant_branch_type_services WHERE merchant_branch_id = 365");
$b366 = queryCount($pdo,"SELECT COUNT(*) AS cnt FROM merchant_branch_type_services WHERE merchant_branch_id = 366");
echo "Branch 365: $b365\n";
echo "Branch 366: $b366\n";

echo "\n=== Service 1 details ===\n";
$svc1 = queryRow($pdo,"SELECT id,name,status FROM type_services WHERE id = 1");
echo "id={$svc1['id']} name={$svc1['name']} status={$svc1['status']}\n";

echo "\n=== Package 1 details ===\n";
$pkg1 = queryRow($pdo,"SELECT id,title,commission,status FROM packages WHERE id = 1");
echo "id={$pkg1['id']} title={$pkg1['title']} commission={$pkg1['commission']} status={$pkg1['status']}\n";