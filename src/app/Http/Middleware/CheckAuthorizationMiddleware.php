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
        $access = $request->app('access'); // public | admin | judge
        if ($access === 'public') return;

        // ERROR: Not authorized!
        if (!Authorization::check($access, Authorization::level())) {
            throw new AuthorizationException();
        }
    }
}
