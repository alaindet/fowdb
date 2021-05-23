<?php

namespace App\Core\Middlewares;

use Firebase\JWT\JWT;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

use App\Core\Exceptions\HttpExceptionFactory;

class AuthenticationMiddleware implements MiddlewareInterface
{
    public function process(
        Request $request,
        RequestHandler $handler
    ): ResponseInterface
    {
        $authorizationHeader = $request->getHeader('Authorization');

        if (empty($authorizationHeader)) {
            $message = 'You are not authorized to perform this action.';
            throw HttpExceptionFactory::unauthorized($message);
        }

        try {
            [$bearer, $token] = explode(' ', $authorizationHeader[0]);
            $authData = JWT::decode($token, $_ENV['APP_JWT_SECRET'], ['HS256']);
            $request = $request->withAttribute('jwt', $authData);
        } catch (\Exception $exception) {
            // $message = $exception->getMessage();
            $message = 'You are not authorized';
            throw HttpExceptionFactory::unauthorized($message);
        }

        return $handler->handle($request);
    }
}
