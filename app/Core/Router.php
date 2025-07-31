<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $params = [];

    public function get($route, $handler)
    {
        $this->addRoute('GET', $route, $handler);
    }

    public function post($route, $handler)
    {
        $this->addRoute('POST', $route, $handler);
    }

    private function addRoute($method, $route, $handler)
    {
        $this->routes[] = [
            'method' => $method,
            'route' => $route,
            'handler' => $handler
        ];
    }

    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = $this->getRequestUri();

        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $this->matchRoute($route['route'], $requestUri)) {
                return $this->callHandler($route['handler']);
            }
        }

        // No route found - 404
        http_response_code(404);
        $this->render404();
    }

    private function getRequestUri()
    {
        $uri = $_SERVER['REQUEST_URI'];
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        return rtrim($uri, '/') ?: '/';
    }

    private function matchRoute($routePattern, $requestUri)
    {
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $routePattern);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $requestUri, $matches)) {
            array_shift($matches); // Remove full match
            $this->params = $matches;
            return true;
        }

        return false;
    }

    private function callHandler($handler)
    {
        list($controllerName, $method) = explode('@', $handler);
        
        // Add namespace
        if (strpos($controllerName, 'Admin\\') === 0) {
            $controllerClass = 'App\\Controllers\\' . $controllerName;
        } else {
            $controllerClass = 'App\\Controllers\\' . $controllerName;
        }

        if (!class_exists($controllerClass)) {
            throw new \Exception("Controller {$controllerClass} not found");
        }

        $controller = new $controllerClass();
        
        if (!method_exists($controller, $method)) {
            throw new \Exception("Method {$method} not found in {$controllerClass}");
        }

        return call_user_func_array([$controller, $method], $this->params);
    }

    private function render404()
    {
        if (file_exists(APP_PATH . '/Views/errors/404.php')) {
            include APP_PATH . '/Views/errors/404.php';
        } else {
            echo '<h1>404 - Không tìm thấy trang</h1>';
        }
    }
}
