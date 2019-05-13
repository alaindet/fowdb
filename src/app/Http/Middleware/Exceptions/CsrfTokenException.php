<?php

namespace App\Http\Middleware\Exceptions;

use App\Base\Exceptions\Exception;
use App\Base\Exceptions\Alertable;

class CsrfTokenException extends Exception implements Alertable
{
    public function __construct()
    {
        parent::__construct('Invalid or missing anti-CSRF token.');
    }
}
