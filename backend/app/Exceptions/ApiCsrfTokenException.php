<?php

namespace App\Exceptions;

use App\Base\Exception;
use App\Exceptions\Jsonable;

class ApiCsrfTokenException extends Exception implements Jsonable
{
    public function __construct()
    {
        parent::__construct('Invalid or missing anti-CSRF token.');
    }
}
