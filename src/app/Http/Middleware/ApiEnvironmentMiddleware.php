<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;
use App\Http\Middleware\MiddlewareInterface;
use App\Services\Configuration\Configuration;

class ApiEnvironmentMiddleware implements MiddlewareInterface
{
    public function run(Request $request): void
    {
        (Configuration::getInstance())->set("current.mode", "api");
    }
}
