<?php
$pdo = new PDO("mysql:host=127.0.0.1;dbname=taqa360_solutions_platform","root","");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sql = "SELECT COUNT(*) AS cnt FROM drivers WHERE is_complete = 0 AND status != 'rejected'";
$stmt = $pdo->query($sql);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
echo "Pending active drivers (is_complete=0 & status!=rejected): " . ($row ? $row['cnt'] : "0") . "\n";
$sql2 = "SELECT id, name, is_complete, status FROM drivers WHERE is_complete = 0 AND status != 'rejected'";
$stmt2 = $pdo->query($sql2);
while ($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    echo "id=" . $r['id'] . " name=" . $r['name'] . " is_complete=" . $r['is_complete'] . " status=" . $r['status'] . "\n";
}