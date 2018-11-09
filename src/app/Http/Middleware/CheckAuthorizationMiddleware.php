<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;
use App\Http\Middleware\MiddlewareInterface;
use App\Exceptions\AuthorizationException;

class CheckAuthorizationMiddleware implements MiddlewareInterface
{
    public function run(Request $request): void
    {
        $requiredRole = $request->app('access');

        // Shortcut for public routes
        if ($requiredRole === 'public') return;

        // ERROR: Current user is not authorized
        if (!auth()->check($requiredRole)) throw new AuthorizationException();
    }
}
