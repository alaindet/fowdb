<?php

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;

use App\Core\Exceptions\ExceptionHandler;

require __DIR__ . '/vendor/autoload.php';

// Environment configuration
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// App setup
$app = AppFactory::create();
$app->addRoutingMiddleware();
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler(ExceptionHandler::class);

// Register routes
require __DIR__ . '/routes.php';

// Go!
$app->run();
