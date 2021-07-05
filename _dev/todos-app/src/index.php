<?php

use App\Core\Http\Request;

require __DIR__ . '/vendor/autoload.php';

// Initialize request
$request = new Request();
$request->computeBody();
$response = null;

switch ($_GET['uri']) {

    case 'todos/create':
        $controller = new \App\Features\Todos\Controllers\TodosController();
        $body = $request->getBody();
        $response = $controller->create($body);
        break;

    case 'todos/all':
        $controller = new \App\Features\Todos\Controllers\TodosController();
        $response = $controller->getAll();
        break;

    case 'todos/single':
        $controller = new \App\Features\Todos\Controllers\TodosController();
        $id = $_GET['id'];
        $response = $controller->getOne($id);
        break;

    case 'todos/update':
        $controller = new \App\Features\Todos\Controllers\TodosController();
        $id = $_GET['id'];
        $body = $request->getBody();
        $response = $controller->update($id, $body);
        break;

    case 'todos/delete':
        $controller = new \App\Features\Todos\Controllers\TodosController();
        $id = $_GET['id'];
        $response = $controller->delete($id);
        break;
}

echo $response;
