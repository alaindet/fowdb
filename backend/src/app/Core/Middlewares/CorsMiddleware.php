<?php

namespace App\Core\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class CorsMiddleware implements MiddlewareInterface
{
    public function process(
        Request $request,
        RequestHandler $handler
    ): ResponseInterface
    {
        $response = $handler->handle($request);

        $origin = $_ENV['APP_CORS_ORIGIN'];
        $methods = $_ENV['APP_CORS_METHODS'];
        $cacheAge = $_ENV['APP_CORS_CACHE_AGE'];
        $cacheHeaders = $_ENV['APP_CORS_CACHE_HEADERS'];

        return $response
            ->withHeader('Access-Control-Allow-Origin', $origin)
            ->withHeader('Access-Control-Allow-Methods', $methods)
            ->withHeader('Access-Control-Max-Age', $cacheAge)
            ->withHeader('Access-Control-Allow-Headers', $cacheHeaders);
    }
}
