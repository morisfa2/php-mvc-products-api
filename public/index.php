<?php

declare(strict_types=1);

use App\Controller\ProductController;
use DI\ContainerBuilder;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Set up error handling
error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG']);

// Set up Dependency Injection
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions(__DIR__ . '/../src/Config/dependencies.php');
$container = $containerBuilder->build();

// Set up routing
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    // Product routes
    $r->addRoute('GET', '/products', [ProductController::class, 'list']);
    $r->addRoute('POST', '/products', [ProductController::class, 'create']);
    $r->addRoute('GET', '/products/{id}', [ProductController::class, 'get']);
    $r->addRoute('PATCH', '/products/{id}', [ProductController::class, 'update']);
    $r->addRoute('DELETE', '/products/{id}', [ProductController::class, 'delete']);
});

// Handle the request
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

try {
    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            
            // Resolve controller from container
            $controller = $container->get($handler[0]);
            $method = $handler[1];
            
            // Call controller method with route parameters
            $controller->$method(...array_values($vars));
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 