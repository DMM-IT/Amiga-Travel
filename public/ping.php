<?php
// ── Bare PHP diagnostic (no Laravel bootstrap) ────────────────────────────────
header('Content-Type: text/plain; charset=utf-8');
ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "=== PHP-FPM IS ALIVE ===\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Date: " . date('Y-m-d H:i:s T') . "\n\n";

// ── .env file contents (safe keys only) ──────────────────────────────────────
$envPath = dirname(__DIR__) . '/.env';
echo "=== .env file ===\n";
if (file_exists($envPath)) {
    $env = file_get_contents($envPath);
    $lines = explode("\n", $env);
    $safeKeys = ['APP_ENV','APP_DEBUG','APP_URL','APP_KEY','DB_CONNECTION','DB_HOST','DB_PORT','DB_DATABASE','SESSION_DRIVER','CACHE_STORE','MAIL_MAILER','FIREBASE_CREDENTIALS'];
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') continue;
        foreach ($safeKeys as $key) {
            if (str_starts_with($line, $key . '=')) {
                if ($key === 'APP_KEY') {
                    $val = explode('=', $line, 2)[1] ?? '';
                    echo "APP_KEY=<set, length=" . strlen($val) . ">\n";
                } else {
                    echo $line . "\n";
                }
                break;
            }
        }
    }
} else {
    echo "ERROR: .env file NOT FOUND at $envPath\n";
}

// ── Extensions ────────────────────────────────────────────────────────────────
echo "\n=== Extensions ===\n";
$needed = ['pdo', 'pdo_mysql', 'mbstring', 'zip', 'bcmath', 'intl', 'gd', 'opcache'];
foreach ($needed as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? 'OK' : 'MISSING') . "\n";
}

// ── Storage ───────────────────────────────────────────────────────────────────
echo "\n=== Storage directory ===\n";
$storagePath = dirname(__DIR__) . '/storage';
echo "storage/ writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";

$bootstrapCache = dirname(__DIR__) . '/bootstrap/cache';
$files = glob($bootstrapCache . '/*.php') ?: [];
echo "bootstrap/cache/ files: " . (empty($files) ? 'NONE' : implode(', ', array_map('basename', $files))) . "\n";

$configCache = $bootstrapCache . '/config.php';
if (file_exists($configCache)) {
    echo "config.php cache: EXISTS (" . number_format(filesize($configCache)) . " bytes)\n";
} else {
    echo "config.php cache: NOT CACHED\n";
}

// ── Database (raw PDO) ────────────────────────────────────────────────────────
echo "\n=== Database (raw PDO) ===\n";
$dbHost = null; $dbPort = null; $dbName = null; $dbUser = null; $dbPass = null;
if (file_exists($envPath)) {
    foreach (explode("\n", file_get_contents($envPath)) as $line) {
        [$k, $v] = array_pad(explode('=', trim($line), 2), 2, '');
        match($k) {
            'DB_HOST'     => $dbHost = $v,
            'DB_PORT'     => $dbPort = $v,
            'DB_DATABASE' => $dbName = $v,
            'DB_USERNAME' => $dbUser = $v,
            'DB_PASSWORD' => $dbPass = $v,
            default       => null,
        };
    }
}
try {
    $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8", $dbUser, $dbPass, [
        PDO::ATTR_TIMEOUT => 5,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $important = array_intersect(['sessions', 'cache', 'users', 'migrations'], $tables);
    echo "Connection: OK (DB=$dbName on $dbHost:$dbPort)\n";
    echo "Key tables: " . (empty($important) ? 'NONE FOUND' : implode(', ', $important)) . "\n";
} catch (\Throwable $e) {
    echo "Connection: FAILED - " . $e->getMessage() . "\n";
}

// ── Firebase auth.json file check ─────────────────────────────────────────────
echo "\n=== Firebase auth.json ===\n";
$fbPath = dirname(__DIR__) . '/storage/firebase-auth.json';
if (file_exists($fbPath)) {
    $content = file_get_contents($fbPath);
    $firstChar = substr(ltrim($content), 0, 1);
    echo "File exists: YES (" . strlen($content) . " bytes)\n";
    echo "First char: '" . $firstChar . "' " . ($firstChar === '{' ? "✅ valid JSON start" : "❌ NOT a JSON object") . "\n";
    $decoded = json_decode($content, true);
    echo "json_decode: " . ($decoded !== null ? "✅ OK (type=" . ($decoded['type'] ?? 'unknown') . ")" : "❌ FAILED (error: " . json_last_error_msg() . ")") . "\n";
} else {
    echo "File: NOT FOUND at $fbPath\n";
}

// ── Try to boot Laravel ───────────────────────────────────────────────────────
echo "\n=== Laravel Boot Test ===\n";
$steps = [];
try {
    define('LARAVEL_START', microtime(true));
    $steps[] = 'LARAVEL_START defined';

    require __DIR__ . '/../vendor/autoload.php';
    $steps[] = '✅ vendor/autoload.php loaded';

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $steps[] = '✅ bootstrap/app.php loaded';

    $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
    $steps[] = '✅ HTTP Kernel created';

    // Boot just the service providers without handling a request
    $app->boot();
    $steps[] = '✅ App booted (all service providers)';

    // Try to run config:cache artisan command
    $exitCode = \Illuminate\Support\Facades\Artisan::call('config:cache');
    $steps[] = '✅ config:cache exit_code=' . $exitCode;

    echo implode("\n", $steps) . "\n";
} catch (\Throwable $e) {
    echo implode("\n", $steps) . "\n";
    echo "\n❌ LARAVEL BOOT FAILED:\n";
    echo "Exception: " . get_class($e) . "\n";
    echo "Message:   " . $e->getMessage() . "\n";
    echo "File:      " . $e->getFile() . "\n";
    echo "Line:      " . $e->getLine() . "\n";
    echo "\nStack trace (first 10 frames):\n";
    $trace = $e->getTrace();
    foreach (array_slice($trace, 0, 10) as $i => $frame) {
        $file = $frame['file'] ?? 'unknown';
        $line = $frame['line'] ?? '?';
        $func = ($frame['class'] ?? '') . ($frame['type'] ?? '') . ($frame['function'] ?? '');
        // Strip /var/www/html/ prefix for readability
        $file = str_replace('/var/www/html/', '', $file);
        echo "#$i $file:$line → $func()\n";
    }
}

echo "\n=== Done ===\n";
