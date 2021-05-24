<?php

use Slim\Routing\RouteCollectorProxy as Group;

use App\Core\Controllers\HomeController;
// use App\Core\Middlewares\CorsMiddleware;

// $app->group('/', function (Group $group) {

//     // Home
//     $group->get('/', HomeController::class . ':home');

//     // Users
//     $users = require __DIR__ . '/app/Features/Users/routes.php';
//     $users($group);

// })
// ->add(CorsMiddleware::class);

// Home
$app->get('/', HomeController::class . ':home');

// Users
$users = require __DIR__ . '/app/Features/Users/routes.php';
$users($app);
