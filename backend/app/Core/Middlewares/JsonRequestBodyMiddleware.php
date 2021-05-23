<?php

namespace App\Core\Middlewares;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use App\Core\Exceptions\HttpExceptionFactory;

class JsonRequestBodyMiddleware implements MiddlewareInterface
{
    public function process(
        Request $request,
        RequestHandler $handler
    ): ResponseInterface
    {
        $contentType = $request->getHeaderLine('Content-Type');

        if (strstr($contentType, 'application/json') === false) {
            $message = 'Content-Type is not application/json';
            throw HttpExceptionFactory::unsupportedMediaType($message);
        }

        $body = json_decode(file_get_contents('php://input'));

        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = 'Invalid JSON content';
            throw HttpExceptionFactory::badRequest($message);
        }

        $request = $request->withParsedBody($body);

        return $handler->handle($request);
    }
}
