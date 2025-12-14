<?php
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', __DIR__);
 
// Load .env into $_ENV (simple loader for shared hosting)
// Must run before config is loaded
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile) && is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $trim = trim($line);
        if ($trim === '' || str_starts_with($trim, '#')) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            // Strip optional quotes
            $value = trim($value, "\"'\"");
            $_ENV[$key] = $value;
        }
    }
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Use statements
use App\Core\App;

// Load configuration and initialize application
try {
    $config = require_once CONFIG_PATH . '/app.php';
    
    $app = new App($config);
    $app->run();
} catch (\Throwable $e) {
    // Log error with stacktrace for debugging
    error_log(
        "Application Error: " . $e->getMessage() .
        " | File: " . $e->getFile() .
        " | Line: " . $e->getLine() .
        "\n" . $e->getTraceAsString()
    );

    // Show error page
    http_response_code(500);
    if (file_exists(APP_PATH . '/Views/errors/500.php')) {
        // Expose message only in debug mode
        $message = null;
        if (!empty($config) && !empty($config['app_debug'])) {
            $message = $e->getMessage() . ' (at ' . $e->getFile() . ':' . $e->getLine() . ')';
        }
        include APP_PATH . '/Views/errors/500.php';
    } else {
        echo '<h1>Lỗi hệ thống</h1><p>Có lỗi xảy ra, vui lòng thử lại sau.</p>';
        echo '<p>Chi tiết lỗi: ' . $e->getMessage() . '</p>';
        echo '<p>File: ' . $e->getFile() . ' Line: ' . $e->getLine() . '</p>';
    }
}