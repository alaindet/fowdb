<?php

namespace App\Legacy\Exceptions;

use App\Base\Exceptions\Exception;
use App\Base\Exceptions\Alertable;

class AuthenticationException extends Exception implements Alertable
{
    public $redirectTo = "login";
}
