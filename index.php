<?php

require __DIR__ . '/src/bootstrap.php';

/*
 | ----------------------------------------------------------------------------
 |
 | Manage old URLs here
 |
 | If old URLs have an equivalent new URL, redirect
 | If not, execute the old template
 |
 | Do *NOT* use 'p' or 'do' GET params at all costs, since they're used
 | in legacy code to provide routing
 |
 | ----------------------------------------------------------------------------
 */
if (isset($_GET['p']) || isset($_GET['do'])) {
    return (new \App\Legacy\Router\Router)->run();
}

// Generate HTTP request
$request = (new \App\Http\Request\Request)
    ->baseUrl('/')
    ->method($_SERVER['REQUEST_METHOD'] ?? 'GET')
    ->host($_SERVER['HTTP_HOST'] ?? config('app.host'))
    ->scheme($_SERVER['REQUEST_SCHEME'] ?? 'http')
    ->httpPort(80)
    ->httpsPort(443)
    ->path($_SERVER['REQUEST_URI'] ?? '/')
    ->queryString($_SERVER['QUERY_STRING']);

// Read the routes
$routes = \App\Services\FileSystem::loadFile(path_data('app/routes.php'));

// Map request to its route
$route = (new \App\Http\Response\Router())
    ->setRoutes($routes)
    ->setRequest($request)
    ->match();

// Set needed access level
$request->app('access', $route['_access']);

// Initialize the dispatcher
$response = (new \App\Http\Response\Dispatcher())
    ->setRequest($request)
    ->setMatchedRoute($route)
    ->runMiddleware()
    ->dispatch();

echo $response;
