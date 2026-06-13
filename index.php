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


// Define Routes - Authentication
$router->get('/login', [\App\Controllers\AuthController::class, 'login']);
$router->post('/login/submit', [\App\Controllers\AuthController::class, 'authenticate']);
$router->get('/login/2fa', [\App\Controllers\AuthController::class, 'show2fa']);
$router->post('/login/2fa/submit', [\App\Controllers\AuthController::class, 'verify2fa']);
$router->get('/logout', [\App\Controllers\AuthController::class, 'logout']);

// URL Shortener API
$router->post('/api/shorten', [\App\Controllers\UrlController::class, 'shorten']);

// Quote Generator APIs
$router->post('/api/quotes/add', [\App\Controllers\QuoteController::class, 'add']);
$router->get('/api/quotes/generate', [\App\Controllers\QuoteController::class, 'generate']);
$router->get('/api/quotes/all', [\App\Controllers\QuoteController::class, 'allApproved']);

// Speed Test APIs
$router->get('/api/speedtest/download', [\App\Controllers\SpeedTestController::class, 'download']);
$router->post('/api/speedtest/upload', [\App\Controllers\SpeedTestController::class, 'upload']);

// Usage Analytics API
$router->post('/api/stats/track-time', [\App\Controllers\StatsController::class, 'trackTime']);

// Define Routes - Admin Panel
$adminPrefix = '/' . \App\Config\App::adminPrefix();

$router->get($adminPrefix . '/dashboard', [\App\Controllers\AdminController::class, 'dashboard']);
$router->get($adminPrefix . '/stats', [\App\Controllers\AdminController::class, 'stats']);

$router->get($adminPrefix . '/tools', [\App\Controllers\AdminController::class, 'tools']);
$router->get($adminPrefix . '/tools/edit/{id}', [\App\Controllers\AdminController::class, 'editTool']);
$router->post($adminPrefix . '/tools/update/{id}', [\App\Controllers\AdminController::class, 'updateTool']);

$router->get($adminPrefix . '/categories', [\App\Controllers\AdminController::class, 'categories']);
$router->get($adminPrefix . '/categories/edit/{id}', [\App\Controllers\AdminController::class, 'editCategory']);
$router->post($adminPrefix . '/categories/update/{id}', [\App\Controllers\AdminController::class, 'updateCategory']);

$router->get($adminPrefix . '/urls', [\App\Controllers\AdminController::class, 'urls']);
$router->get($adminPrefix . '/settings', [\App\Controllers\AdminController::class, 'settings']);
$router->post($adminPrefix . '/settings/password', [\App\Controllers\AdminController::class, 'updatePassword']);
$router->post($adminPrefix . '/urls/delete', [\App\Controllers\AdminController::class, 'deleteUrls']);

// Admin - Feature Requests Management
$router->get($adminPrefix . '/features', [\App\Controllers\AdminController::class, 'features']);
$router->post($adminPrefix . '/features/solve', [\App\Controllers\AdminController::class, 'solveFeature']);
$router->post($adminPrefix . '/features/delete', [\App\Controllers\AdminController::class, 'deleteFeature']);

// Admin - Quotes Management
$router->get($adminPrefix . '/quotes', [\App\Controllers\AdminController::class, 'quotes']);
$router->post($adminPrefix . '/quotes/approve', [\App\Controllers\AdminController::class, 'approveQuote']);
$router->post($adminPrefix . '/quotes/delete', [\App\Controllers\AdminController::class, 'deleteQuotes']);




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
