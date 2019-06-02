<?php

use App\Legacy\Router\Router as LegacyRouter;
use App\Services\FileSystem\FileSystem;
use App\Http\Request\Request;
use App\Http\Response\Router;
use App\Http\Response\Dispatcher;
use App\Utils\Paths;
use App\Services\Configuration\Configuration;

require __DIR__ . "/src/bootstrap.php";

/*
 | ----------------------------------------------------------------------------
 |
 | Manage old URLs here
 |
 | If old URLs have an equivalent new URL, redirect
 | If not, execute the old template
 |
 | Do *NOT* use "p" or "do" GET params at all costs, since they"re used
 | in legacy code to provide routing
 |
 | ----------------------------------------------------------------------------
 */
if (isset($_GET["p"]) || isset($_GET["do"])) {
    return (new LegacyRouter)->run();
}

// Instantiate the configuration service
$config = Configuration::getInstance();

// Generate HTTP request
$request = (new Request)
    ->setBaseUrl("/")
    ->setMethod($_SERVER["REQUEST_METHOD"] ?? "GET")
    ->setHost($_SERVER["HTTP_HOST"] ?? $config->get("app.host"))
    ->setScheme($_SERVER["REQUEST_SCHEME"] ?? "http")
    ->setHttpPort(80)
    ->setHttpsPort(443)
    ->setPath($_SERVER["REQUEST_URI"] ?? "/")
    ->setQueryString($_SERVER["QUERY_STRING"]);

// Read the routes
$routes = FileSystem::loadFile(Paths::inDataDir("app/routes.php"));

// TEST
require __DIR__ . "/src/add-test-routes.php";

// Map request to its route
$route = (new Router)
    ->setRoutes($routes)
    ->setRequest($request)
    ->match();

// Store current state for this request
$config->set("current.access", $route["_access"]);
$config->set("current.mode", "web");

// Initialize the dispatcher
$response = (new Dispatcher)
    ->setRequest($request)
    ->setMatchedRoute($route)
    ->runMiddleware()
    ->dispatch();

echo $response;
