<?php

namespace App\Http\Middleware\Exceptions;

use App\Base\Exception;

class ApiAuthorizationException extends Exception
{
    public function __construct()
    {
        parent::__construct(
            "You are not authorized to perform this action."
        );
    }
}
