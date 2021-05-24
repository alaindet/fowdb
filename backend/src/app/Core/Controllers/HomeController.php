<?php

namespace App\Core\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HomeController {
    public function home(Request $request, Response $response): Response
    {
        $response->getBody()->write($_ENV['APP_NAME']);
        return $response;
    }
}
