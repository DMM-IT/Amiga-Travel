<?php
// Raw PHP-FPM test - no Laravel bootstrap, no autoloader
// This file should respond even if Laravel is completely broken
header('Content-Type: text/plain; charset=utf-8');

echo "=== PHP-FPM IS ALIVE ===\n\n";
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Date: " . date('Y-m-d H:i:s T') . "\n\n";

// Check .env file
$envPath = dirname(__DIR__) . '/.env';
echo "=== .env file ===\n";
if (file_exists($envPath)) {
    $env = file_get_contents($envPath);
    $lines = explode("\n", $env);
    // Show only safe lines (no passwords)
    $safeKeys = ['APP_ENV','APP_DEBUG','APP_URL','APP_KEY','DB_CONNECTION','DB_HOST','DB_PORT','DB_DATABASE','SESSION_DRIVER','CACHE_STORE','MAIL_MAILER','FIREBASE_CREDENTIALS'];
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line) || $line[0] === '#') continue;
        foreach ($safeKeys as $key) {
            if (str_starts_with($line, $key . '=')) {
                // Mask sensitive parts
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

echo "\n=== Extensions ===\n";
$needed = ['pdo', 'pdo_mysql', 'mbstring', 'zip', 'bcmath', 'intl', 'gd', 'opcache'];
foreach ($needed as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? 'OK' : 'MISSING') . "\n";
}

echo "\n=== Storage directory ===\n";
$storagePath = dirname(__DIR__) . '/storage';
echo "storage/ writable: " . (is_writable($storagePath) ? 'YES' : 'NO') . "\n";

$bootstrapCache = dirname(__DIR__) . '/bootstrap/cache';
$files = glob($bootstrapCache . '/*.php');
echo "bootstrap/cache/ files: " . implode(', ', array_map('basename', $files ?: [])) . "\n";

// Check for cached config
$configCache = $bootstrapCache . '/config.php';
if (file_exists($configCache)) {
    echo "config.php cache: EXISTS (" . number_format(filesize($configCache)) . " bytes)\n";
    // Try to include it and catch errors
    try {
        $cfg = include $configCache;
        echo "config.php cache: VALID (array=" . (is_array($cfg) ? 'yes' : 'no') . ")\n";
    } catch (\Throwable $e) {
        echo "config.php cache: BROKEN - " . $e->getMessage() . "\n";
    }
} else {
    echo "config.php cache: NOT CACHED\n";
}

echo "\n=== Database (raw PDO) ===\n";
// Read from .env manually
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
    echo "Connection: OK (DB=$dbName on $dbHost:$dbPort)\n";
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $important = array_intersect(['sessions', 'cache', 'users', 'migrations'], $tables);
    echo "Key tables: " . (empty($important) ? 'NONE FOUND' : implode(', ', $important)) . "\n";
} catch (\Throwable $e) {
    echo "Connection: FAILED - " . $e->getMessage() . "\n";
}

echo "\n=== Done ===\n";
