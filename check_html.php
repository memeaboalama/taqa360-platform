<?php
$html = file_get_contents('http://127.0.0.1:8000/');
echo "HTML length: " . strlen($html) . "\n";
echo "Has 'extra-section': " . (strpos($html, 'extra-section') !== false ? 'YES' : 'NO') . "\n";
echo "Has 'class=\"product\"': " . (strpos($html, 'class="product"') !== false ? 'YES' : 'NO') . "\n";
echo "Has 'کورس' or 'Python': " . (strpos($html, 'Python') !== false ? 'YES' : 'NO') . "\n";

// Print around extra-section
if (preg_match('/<section id="best-sellers".*?<\/section>/s', $html, $matches)) {
    echo "\n=== BEST SELLERS SECTION HTML ===\n";
    echo substr($matches[0], 0, 1000) . "\n";
} else {
    echo "No best-sellers match\n";
}
