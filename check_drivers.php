<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=taqa360_solutions_platform","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT COUNT(*) AS cnt FROM drivers WHERE is_complete = 0";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Pending drivers (is_complete=0): " . ($row ? $row['cnt'] : "0") . "\n";

$sql2 = "SELECT COUNT(*) AS cnt FROM drivers";
$stmt2 = $pdo->query($sql2);
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
echo "Total drivers: " . ($row2 ? $row2['cnt'] : "0") . "\n";

$sql3 = "SELECT id, name, is_complete, status FROM drivers LIMIT 10";
$stmt3 = $pdo->query($sql3);
while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
    echo "id=" . $row3['id'] . " name=" . $row3['name'] . " is_complete=" . $row3['is_complete'] . " status=" . $row3['status'] . "\n";
}