<?php

use Slim\App;

use App\Features\Users\Controllers\UsersController;
use App\Core\Middlewares\JsonRequestBodyMiddleware;
use App\Core\Middlewares\AuthenticationMiddleware;

return function(App $app): void
{
    $controller = UsersController::class;

    $app->post('/users/login', "{$controller}:login")
    ->add(JsonRequestBodyMiddleware::class);

    $app->post('/users/register', "{$controller}:register")
        ->add(JsonRequestBodyMiddleware::class);

    $app->get('/users/list', "{$controller}:list")
        ->add(AuthenticationMiddleware::class);
};
