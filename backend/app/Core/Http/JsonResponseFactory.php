<?php

namespace App\Core\Http;

use Slim\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

abstract class JsonResponseFactory
{
    static public function from(
        ResponseInterface $response,
        $data
    ): ResponseInterface
    {
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    static public function create(
        $data,
        int $statusCode = 200
    ): ResponseInterface
    {
        $response = new Response();
        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
