<?php

// =========================================
// ERROR REPORTING (DEV ONLY)
// =========================================
if (env('APP_ENV') === 'local') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// =========================================
// LOAD CONFIG (BOOTSTRAP EVERYTHING)
// =========================================
require_once __DIR__ . "/../config/config.php";

// =========================================
// BASIC REQUEST OBJECT
// =========================================
$request = [
    'method' => $_SERVER['REQUEST_METHOD'],
    'uri'    => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
    'query'  => $_GET,
    'body'   => $_POST
];

// Remove base path if in subfolder
$basePath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$request['uri'] = '/' . trim(str_replace($basePath, '', $request['uri']), '/');

// =========================================
// LOAD ROUTES
// =========================================
$routes = require __DIR__ . "/../routes/web.php";

// =========================================
// SIMPLE ROUTER
// =========================================
function matchRoute($routes, $request) {

    foreach ($routes as $route) {

        [$method, $path, $action, $middlewares] = $route;

        if ($method !== $request['method']) continue;

        $pattern = preg_replace('#\{[^/]+\}#', '([^/]+)', $path);
        $pattern = "#^" . $pattern . "$#";

        if (preg_match($pattern, $request['uri'], $matches)) {

            array_shift($matches); // remove full match

            return [
                'action' => $action,
                'params' => $matches,
                'middlewares' => $middlewares
            ];
        }
    }

    return null;
}

$route = matchRoute($routes, $request);

if (!$route) {
    http_response_code(404);
    echo "404 Not Found";
    exit();
}

// =========================================
// MIDDLEWARE PIPELINE
// =========================================
require_once "../app/middleware/Pipeline.php";

$pipeline = new Pipeline($route['middlewares'] ?? []);

$response = $pipeline->handle($request, function ($request) use ($route) {

    [$controller, $method] = explode('@', $route['action']);

    if (!class_exists($controller)) {
        throw new Exception("Controller not found: $controller");
    }

    $instance = new $controller();

    if (!method_exists($instance, $method)) {
        throw new Exception("Method not found: $method");
    }

    return call_user_func_array([$instance, $method], $route['params']);
});

// =========================================
// FINAL RESPONSE (OPTIONAL HANDLING)
// =========================================
if (is_array($response) || is_object($response)) {
    header('Content-Type: application/json');
    echo json_encode($response);
}