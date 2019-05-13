<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;
use App\Http\Middleware\MiddlewareInterface;
use App\Exceptions\ApiAuthorizationException;
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
        if (!fd_auth()->check($requiredRole)) {
            throw new ApiAuthorizationException();
        }
    }
}
