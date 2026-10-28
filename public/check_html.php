<?php
// Load Laravel home page internally without HTTP loop
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::create('/', 'GET')
);

$html = $response->getContent();
echo "STATUS: " . $response->getStatusCode() . "\n";
echo "HTML length: " . strlen($html) . "\n";
echo "Has 'extra-section': " . (strpos($html, 'extra-section') !== false ? 'YES' : 'NO') . "\n";
echo "Has 'class=\"product\"': " . (strpos($html, 'class="product"') !== false ? 'YES' : 'NO') . "\n";
echo "Has 'Python': " . (strpos($html, 'Python') !== false ? 'YES' : 'NO') . "\n";

if (preg_match('/<section id="best-sellers".*?<\/section>/s', $html, $matches)) {
    echo "\n=== BEST SELLERS SECTION HTML ===\n";
    echo $matches[0] . "\n";
} else {
    echo "No best-sellers match found\n";
}
