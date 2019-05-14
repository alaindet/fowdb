<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;
use App\Http\Middleware\MiddlewareInterface;
use App\Http\Middleware\Exceptions\ApiAuthorizationException;
use App\Legacy\Authorization;
use App\Services\Configuration\Configuration;

class ApiCheckAuthorizationMiddleware implements MiddlewareInterface
{
    public function run(Request $request): void
    {
        $requiredRole = (Configuration::getInstance())->get("current.access");

        // Shortcut for public routes
        if ($requiredRole === "public") {
            return;
        }

        // ERROR: Current user is not authorized
        if (!(Authorization::getInstance())->check($requiredRole)) {
            throw new ApiAuthorizationException();
        }
    }
}
