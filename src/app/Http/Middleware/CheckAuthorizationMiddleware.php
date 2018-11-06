<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;
use App\Http\Middleware\MiddlewareInterface;
use App\Legacy\Authorization;
use App\Exceptions\AuthorizationException;

class CheckAuthorizationMiddleware implements MiddlewareInterface
{
    public function run(Request $request): void
    {
        // Access levels:
        // 'public' => 0,
        // 'admin' => 1,
        // 'user' => 2,
        // 'judge' => 3
        $requiredLevel = $request->app('access');

        // Bypass access control
        if ($requiredLevel === 'public') return;

        // ERROR: Not authorized!
        if (!Authorization::check($requiredLevel)) {
            throw new AuthorizationException();
        }
    }
}
