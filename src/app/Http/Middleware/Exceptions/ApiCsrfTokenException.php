<?php

namespace App\Http\Middleware\Exceptions;

use App\Base\Exception;

class ApiCsrfTokenException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            "Invalid or missing anti-CSRF token."
        );
    }
}
