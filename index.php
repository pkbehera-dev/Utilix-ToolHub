<?php
define('SECURE_ACCESS', true);
/**
 * ToolBox Front Controller
 * All frontend requests are routed through here.
 */

// Basic error reporting for development (disable in production)
ini_set('display_errors', 0); // Forced 0 for security
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

// Global Exception Handler
set_exception_handler(function ($e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo "Something went wrong. Please try again later.";
    exit;
});

// Simple Autoloader mapping App namespace to src/ directory
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Load Environment Variables
try {
    \App\Core\DotEnv::load(__DIR__ . '/.env');
} catch (\Exception $e) {
    die("Environment configuration error: " . $e->getMessage());
}

use App\Core\Router;

$router = new Router();

// Define Routes - Frontend
$router->get('/', [\App\Controllers\HomeController::class, 'index']);
$router->get('/about', [\App\Controllers\HomeController::class, 'about']);
$router->get('/privacy', [\App\Controllers\HomeController::class, 'privacy']);
$router->get('/terms', [\App\Controllers\HomeController::class, 'terms']);
$router->get('/tool/{slug}', [\App\Controllers\HomeController::class, 'tool']);



// URL Shortener API
$router->post('/api/shorten', [\App\Controllers\UrlController::class, 'shorten']);

// Password Hasher APIs
$router->post('/api/password-hasher/hash', [\App\Controllers\PasswordHasherController::class, 'hash']);

// Quote Generator APIs
$router->post('/api/quotes/add', [\App\Controllers\QuoteController::class, 'add']);
$router->get('/api/quotes/generate', [\App\Controllers\QuoteController::class, 'generate']);
$router->get('/api/quotes/all', [\App\Controllers\QuoteController::class, 'allApproved']);

// Speed Test APIs
$router->get('/api/speedtest/download', [\App\Controllers\SpeedTestController::class, 'download']);
$router->post('/api/speedtest/upload', [\App\Controllers\SpeedTestController::class, 'upload']);

// Usage Analytics API
$router->post('/api/stats/track-time', [\App\Controllers\StatsController::class, 'trackTime']);





// Get requested URI
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Feature Request Community Routes
$router->get('/features', [\App\Controllers\FeatureController::class, 'index']);
$router->post('/features/add', [\App\Controllers\FeatureController::class, 'add']);
$router->post('/features/star', [\App\Controllers\FeatureController::class, 'star']);

// Dynamic Short URL route - MUST BE LAST so it doesn't override other routes
$router->get('/{short_code}', [\App\Controllers\UrlController::class, 'redirect']);

// Dispatch Request
$router->dispatch($requestUri, $requestMethod);
