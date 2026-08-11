<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ── TEMPORARY DEBUG WRAPPER ───────────────────────────────────────────────────
// register_shutdown_function fires even on fatal PHP errors (OOM, E_ERROR, etc.)
// that cannot be caught by set_exception_handler.
// Remove once production is stable.
if ((bool) (getenv('APP_DEBUG') === 'true' || getenv('APP_DEBUG') === '1')) {
    ini_set('display_errors', '0'); // Don't auto-output — we handle it manually

    register_shutdown_function(function () {
        $error = error_get_last();
        if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_RECOVERABLE_ERROR])) {
            if (!headers_sent()) {
                http_response_code(500);
                header('Content-Type: text/plain; charset=utf-8');
            }
            echo "\n\n=== PHP FATAL ERROR (shutdown_function) ===\n";
            echo "Type:    " . $error['type'] . "\n";
            echo "Message: " . $error['message'] . "\n";
            echo "File:    " . $error['file'] . "\n";
            echo "Line:    " . $error['line'] . "\n";
        }
    });

    set_exception_handler(function (\Throwable $e) {
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=utf-8');
        }
        echo "=== LARAVEL FATAL BOOT ERROR ===\n\n";
        echo get_class($e) . ": " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . " (line " . $e->getLine() . ")\n\n";
        echo "Stack Trace:\n" . $e->getTraceAsString() . "\n";
    });
}
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
