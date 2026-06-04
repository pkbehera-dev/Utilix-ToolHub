<?php
namespace App\Core;

class Router {
    private array $routes = [];

    /**
     * Register a GET route
     */
    public function get(string $path, array $handler): void {
        $this->addRoute('GET', $path, $handler);
    }

    /**
     * Register a POST route
     */
    public function post(string $path, array $handler): void {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, array $handler): void {
        // Convert path to regex for dynamic parameters (e.g., /tool/{slug})
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_-]+)', $path);
        $pattern = "#^" . $pattern . "$#";
        
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler
        ];
    }

    /**
     * Dispatch the current request
     */
    public function dispatch(string $uri, string $requestMethod): void {
        // Remove query string from URI
        $uri = strtok($uri, '?');
        
        // Remove base path if necessary (e.g. if installed in a subdirectory)
        // Here we assume simple mapping based on how .htaccess handles it
        // Depending on .htaccess, the URI passed might just be the clean path
        $scriptName = dirname($_SERVER['SCRIPT_NAME']); 
        if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
            $uri = substr($uri, strlen($scriptName));
        }
        
        if (empty($uri)) $uri = '/';

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && preg_match($route['pattern'], $uri, $matches)) {
                $controller = $route['handler'][0];
                $method = $route['handler'][1];
                
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (class_exists($controller) && method_exists($controller, $method)) {
                    $instance = new $controller();
                    call_user_func_array([$instance, $method], array_values($params));
                    return;
                }
            }
        }

        // 404 Not Found
        http_response_code(404);
        $pageTitle = 'Page Not Found - ToolBox';
        $metaDescription = 'The page you are looking for does not exist on ToolBox.';
        $contentView = 'pages/404';
        require __DIR__ . '/../Views/layout.php';
    }
}

