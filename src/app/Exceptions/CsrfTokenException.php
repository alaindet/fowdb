<?php

namespace App\Exceptions;

use App\Base\Exception;
use App\Exceptions\Alertable;

class CsrfTokenException extends Exception implements Alertable
{
    public function __construct()
    {
        parent::__construct('Invalid or missing anti-CSRF token.');
    }
}
