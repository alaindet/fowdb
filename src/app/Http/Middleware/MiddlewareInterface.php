<?php

namespace App\Http\Middleware;

use App\Http\Request\Request;

interface MiddlewareInterface
{
    public function run(Request $request): void;
}
