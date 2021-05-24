<?php

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

use App\Core\Exceptions\ExceptionHandler;
use App\Core\Middlewares\CorsMiddleware;

require __DIR__ . '/vendor/autoload.php';

// Environment configuration
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// App setup
$app = AppFactory::create();

// App setup: routing
$app->addRoutingMiddleware();

// App setup: error handling
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(ExceptionHandler::class);

// App setup: CORS
$app->options('/{routes:.+}', function($req, $res) { return $res; });
$app->add(CorsMiddleware::class);

// Register routes
require __DIR__ . '/routes.php';

// Go!
$app->run();
