<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/helpers.php';

use App\Router;
use App\Controllers\UserController;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$router = new Router();
$controller = new UserController();

// API routes
$router->get('/users', [$controller, 'index']);
$router->get('/users/{id}', [$controller, 'show']);
$router->post('/users', [$controller, 'store']);
$router->put('/users/{id}', [$controller, 'update']);
$router->delete('/users/{id}', [$controller, 'delete']);

// View routes
$router->get('/view-users', [$controller, 'index2']);

// Dispatch the request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$router->dispatch($uri, $method);
