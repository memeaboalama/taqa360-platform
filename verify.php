<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=taqa360_solutions_platform","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$q = function($sql) use ($pdo) { return $pdo->query($sql)->fetch()["cnt"]; };
echo "Countries: " . $q("SELECT COUNT(*) AS cnt FROM countries") . "\n";
echo "Regions: " . $q("SELECT COUNT(*) AS cnt FROM regions") . "\n";
echo "Cities: " . $q("SELECT COUNT(*) AS cnt FROM cities") . "\n";
echo "Type services: " . $q("SELECT COUNT(*) AS cnt FROM type_services") . "\n";
echo "Packages: " . $q("SELECT COUNT(*) AS cnt FROM packages") . "\n";
echo "city_type_service_packages: " . $q("SELECT COUNT(*) AS cnt FROM city_type_service_packages") . "\n";