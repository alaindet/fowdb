<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;
use App\Http\Middleware\MiddlewareInterface;
use App\Services\CsrfToken;
use App\Exceptions\ApiCsrfTokenException;

class ApiCheckCsrfTokenMiddleware implements MiddlewareInterface
{
    public function run(Request $request): void
    {
        // Skip GET requests
        if ($request->method() === 'GET') return;

        // Read passed anti-CSRF token
        $token = $request->input()->post(CsrfToken::NAME);

        // ERROR: Missing token
        if (!isset($token)) throw new ApiCsrfTokenException();

        // ERROR: Invalid token
        if (!CsrfToken::check($token)) throw new ApiCsrfTokenException();
    }
}
