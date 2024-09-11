<?php

namespace App;

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'VIEW' => []
    ];

    // Register a GET route
    public function get($uri, $controllerMethod)
    {
        $this->routes['GET'][$uri] = $controllerMethod;
    }

    // Register a POST route
    public function post($uri, $controllerMethod)
    {
        $this->routes['POST'][$uri] = $controllerMethod;
    }

    // Register a PUT route
    public function put($uri, $controllerMethod)
    {
        $this->routes['PUT'][$uri] = $controllerMethod;
    }

    // Register a DELETE route
    public function delete($uri, $controllerMethod)
    {
        $this->routes['DELETE'][$uri] = $controllerMethod;
    }

    // Register a VIEW route
    public function view($uri, $viewFile)
    {
        $this->routes['VIEW'][$uri] = $viewFile;
    }

    // Dispatch the current request
    public function dispatch($uri, $method)
    {
        // Clean up the URI and method
        $uri = rtrim($uri, '/');
        $method = strtoupper($method);

        // If a direct match for the URI and method exists, call the associated controller method
        if (isset($this->routes[$method][$uri])) {
            call_user_func($this->routes[$method][$uri]);
            return;
        }

        // Check for routes with parameters (e.g., /users/{id})
        foreach ($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('/\{\w+\}/', '(\d+)', $route);
            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                array_shift($matches); // Remove the full match from the array
                call_user_func_array($action, $matches);
                return;
            }
        }

        // Check for VIEW routes
        if (isset($this->routes['VIEW'][$uri])) {
            $viewFile = $this->routes['VIEW'][$uri];
            \App\View::render($viewFile);
            return;
        }

        // If no match found, return a 404
        http_response_code(404);
        echo json_encode(['message' => 'Not Found']);
    }
}
