<?php

$pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=huloolcom_edu_solutions_platform;charset=utf8mb4", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "=== ITEMS TABLE ===\n";
$items = $pdo->query("SELECT id, title, status, default_price, sale_price, is_on_sale, deleted_at FROM items")->fetchAll(PDO::FETCH_ASSOC);
echo "Total items: " . count($items) . "\n";
foreach ($items as $item) {
    echo "ID: {$item['id']} | Status: {$item['status']} | Price: {$item['default_price']} | Sale: {$item['sale_price']} | OnSale: {$item['is_on_sale']} | Deleted: {$item['deleted_at']}\n";
}

echo "\n=== AD_PAGES ===\n";
$pages = $pdo->query("SELECT id, `key`, title FROM ad_pages")->fetchAll(PDO::FETCH_ASSOC);
foreach ($pages as $p) {
    echo "ID: {$p['id']} | Key: {$p['key']} | Title: {$p['title']}\n";
}

echo "\n=== AD_PLACEMENTS for home page ===\n";
$placements = $pdo->query("SELECT id, page_id, `key`, type, status FROM ad_placements WHERE page_id = 1")->fetchAll(PDO::FETCH_ASSOC);
foreach ($placements as $pl) {
    echo "ID: {$pl['id']} | Key: {$pl['key']} | Type: {$pl['type']} | Status: {$pl['status']}\n";
}

echo "\n=== AD_PRODUCT_CONTENTS ===\n";
$pc = $pdo->query("SELECT id, placement_id, product_id, group_key, status FROM ad_product_contents")->fetchAll(PDO::FETCH_ASSOC);
echo "Total ad_product_contents: " . count($pc) . "\n";
foreach ($pc as $row) {
    echo "ID: {$row['id']} | Placement: {$row['placement_id']} | Product: {$row['product_id']} | Group: {$row['group_key']} | Status: {$row['status']}\n";
}
