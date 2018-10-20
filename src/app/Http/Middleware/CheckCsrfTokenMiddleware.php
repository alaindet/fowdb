<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;
use App\Http\Middleware\MiddlewareInterface;
use App\Services\CsrfToken;
use App\Exceptions\CsrfTokenException;

class CheckCsrfTokenMiddleware implements MiddlewareInterface
{
    public function run(Request $request): void
    {
        if ($request->method() === 'GET') return;
        $token = $request->input()->post(CsrfToken::NAME);
        if (!isset($token)) throw new CsrfTokenException();
        if (!CsrfToken::check($token)) throw new CsrfTokenException();
    }
}
