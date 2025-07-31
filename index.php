<?php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constants
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Load Composer autoloader
require_once ROOT_PATH . '/vendor/autoload.php';

// Load configuration
$config = require_once CONFIG_PATH . '/app.php';

// Initialize the application
use App\Core\App;

try {
    $app = new App($config);
    $app->run();
} catch (Exception $e) {
    // Log error
    error_log("Application Error: " . $e->getMessage());
    
    // Show error page
    http_response_code(500);
    if (file_exists(APP_PATH . '/Views/errors/500.php')) {
        include APP_PATH . '/Views/errors/500.php';
    } else {
        echo '<h1>Lỗi hệ thống</h1><p>Có lỗi xảy ra, vui lòng thử lại sau.</p>';
    }
}
