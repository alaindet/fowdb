<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;
use App\Http\Middleware\MiddlewareInterface;

class ApiEnvironmentMiddleware implements MiddlewareInterface
{
    public function run(Request $request): void
    {
        config('current.mode', 'api');
    }
}
