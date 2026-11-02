<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=taqa360_solutions_platform","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->query("SELECT COUNT(*) FROM merchant_branch_type_services WHERE merchant_branch_id = 365");
echo "Branch 365 type services: " . $stmt->fetch()["COUNT(*)"] . "\n";
$stmt = $pdo->query("SELECT COUNT(*) FROM merchant_branch_type_services WHERE merchant_branch_id = 366");
echo "Branch 366 type services: " . $stmt->fetch()["COUNT(*)"] . "\n";
$stmt = $pdo->query("SELECT name FROM type_services WHERE id = 1");
echo "Type service 1 name: " . $stmt->fetch()["name"] . "\n";
$stmt = $pdo->query("SELECT title FROM packages WHERE id = 1");
echo "Package 1 title: " . $stmt->fetch()["title"] . "\n";
'