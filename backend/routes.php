<?php

use App\Core\Controllers\HomeController;

// Home
$app->get('/', HomeController::class . ':home');

// Users
$users = require __DIR__ . '/app/Features/Users/routes.php';
$users($app);
