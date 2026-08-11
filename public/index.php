<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ── TEMPORARY DEBUG WRAPPER (remove after production is stable) ───────────────
// Captures any fatal/uncaught Throwable that occurs before Laravel's own
// exception handler is ready, outputting the raw error instead of a blank 500.
set_exception_handler(function (\Throwable $e) {
    $debug = (bool) (getenv('APP_DEBUG') ?: false);
    if ($debug) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
        echo "=== LARAVEL FATAL BOOT ERROR ===\n\n";
        echo get_class($e) . ": " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . " (line " . $e->getLine() . ")\n\n";
        echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
    } else {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        echo '<h1>500 Internal Server Error</h1>';
    }
});

// ─────────────────────────────────────────────────────────────────────────────

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
