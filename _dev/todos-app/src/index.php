<?php

use App\Core\Http\Request;
use App\Core\Services\Configuration\Configuration;

require __DIR__ . '/vendor/autoload.php';

// Initialize configuration
$config = new Configuration(__DIR__ . '/app/config');

// Initialize request
$request = new Request();
$request->computeBody();
$response = null;

$dispatcher = \FastRoute\simpleDispatcher(
    function(\FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/', 'get_home');
        $r->addRoute('GET', '/users', 'get_all_users_handler');
        $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
        $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
    }
);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

\App\Shared\Utils\Utils::dump('req data', [
    'httpMethod' => $httpMethod,
    'uri' => $uri,
]);

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars
        break;
}

\App\Shared\Utils\Utils::dump('routeInfo', $routeInfo);

echo "HELLO WORLD";

// switch ($_GET['uri']) {

//     case 'todos/create':
//         $controller = new \App\Features\Todos\Controllers\TodosController($config);
//         $body = $request->getBody();
//         $response = $controller->create($body);
//         break;

//     case 'todos/all':
//         $controller = new \App\Features\Todos\Controllers\TodosController($config);
//         $response = $controller->getAll();
//         break;

//     case 'todos/single':
//         $controller = new \App\Features\Todos\Controllers\TodosController($config);
//         $id = $_GET['id'];
//         $response = $controller->getOne($id);
//         break;

//     case 'todos/update':
//         $controller = new \App\Features\Todos\Controllers\TodosController($config);
//         $id = $_GET['id'];
//         $body = $request->getBody();
//         $response = $controller->update($id, $body);
//         break;

//     case 'todos/delete':
//         $controller = new \App\Features\Todos\Controllers\TodosController($config);
//         $id = $_GET['id'];
//         $response = $controller->delete($id);
//         break;
// }

// echo $response;
