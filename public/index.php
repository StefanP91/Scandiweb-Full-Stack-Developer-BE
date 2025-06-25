<?php

// Error reporting
$logDir = __DIR__ . '/../logs';
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}

ini_set('error_log', $logDir . '/php_error.log');
ini_set('log_errors', 1);
ini_set('display_errors', 0); 
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

// Set up the FastRoute dispatcher
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->post('/graphql', [App\Controller\GraphQL::class, 'handle']);
});

// Strip query string and fix the URI path
$uri = $_SERVER['REQUEST_URI'];
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$uri = str_replace('/backend/public', '', $uri);

// Dispatch the route
$routeInfo = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("HTTP/1.0 404 Not Found");
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode(['error' => 'Not Found']);
        break;
        
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        header("HTTP/1.0 405 Method Not Allowed");
        header("Content-Type: application/json; charset=UTF-8");
        $allowedMethods = $routeInfo[1];
        echo json_encode(['error' => 'Method Not Allowed', 'allowed' => $allowedMethods]);
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        echo $handler($vars);
        break;    
    }
