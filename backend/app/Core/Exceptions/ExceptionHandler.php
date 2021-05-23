<?php

namespace App\Core\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use Slim\Exception\HttpNotFoundException as SlimHttpNotFoundException;

use App\Core\Exceptions\HttpException;
use App\Core\Http\JsonResponseFactory;

class ExceptionHandler {
    public function __invoke(
        ServerRequestInterface $request,
        \Throwable $exception
    ): Response
    {
        $status = 500;

        if ($exception instanceof HttpException) {
            $status = $exception->getStatusCode();
        } elseif ($exception instanceof SlimHttpNotFoundException) {
            $status = 404;
        }

        return JsonResponseFactory::create([
            'error' => true,
            'message' => $exception->getMessage(),
        ], $status);
    }
}
