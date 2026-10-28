<?php
/**
 * Quick SMTP authentication test against Gmail.
 * Usage:  php _test_mail.php
 * Run this AFTER updating MAIL_USERNAME / MAIL_PASSWORD in .env
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$cfg = config('mail.mailers.smtp');
$host = $cfg['host'] ?? 'smtp.gmail.com';
$port = $cfg['port'] ?? 587;
$user = $cfg['username'] ?? '';
$pass = $cfg['password'] ?? '';

echo "host     : {$host}:{$port}\n";
echo "username : {$user}\n";
echo "pass len : " . strlen($pass) . " chars\n";
echo str_repeat('-', 50) . "\n";

function readResponse($fp): string
{
    $data = '';
    while (($line = fgets($fp, 1024)) !== false) {
        $data .= $line;
        if (isset($line[3]) && $line[3] === ' ') break;
    }
    return trim($data);
}

$context = stream_context_create(['ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'SNI_enabled' => true, 'peer_name' => $host]]);
$fp = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $context);
if (!$fp) { die("❌ Connection failed: {$errstr}\n"); }
echo readResponse($fp) . "\n";

fwrite($fp, "EHLO local.test\r\n");
readResponse($fp);

fwrite($fp, "STARTTLS\r\n");
echo "[Gmail] " . readResponse($fp) . "\n";

if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
    die("❌ STARTTLS failed\n");
}
fwrite($fp, "EHLO local.test\r\n");
readResponse($fp);
echo "✅ TLS established — sending AUTH LOGIN\n";
echo str_repeat('-', 50) . "\n";

fwrite($fp, "AUTH LOGIN\r\n");
echo "[Gmail] " . readResponse($fp) . "\n";

fwrite($fp, base64_encode($user) . "\r\n");
echo "[Gmail] " . readResponse($fp) . "\n";

fwrite($fp, base64_encode($pass) . "\r\n");
$response = readResponse($fp);
echo "[Gmail] " . $response . "\n";

if (strpos($response, '235') === 0) {
    echo "\n🎉 SUCCESS — credentials are valid! Email sending will work now.\n";
} else {
    echo "\n❌ FAILED — Google rejected these credentials.\n";
    echo "   Make sure:\n";
    echo "   1. MAIL_USERNAME is the SAME account you generated the App Password from\n";
    echo "   2. The password is exactly 16 chars, no spaces, no quotes in .env\n";
    echo "   3. Create a FRESH app password at https://myaccount.google.com/apppasswords\n";
}

fwrite($fp, "QUIT\r\n");
fclose($fp);
