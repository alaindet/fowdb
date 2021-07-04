<?php

namespace App\Exceptions;

use App\Base\Exception;
use App\Exceptions\Alertable;

class AuthorizationException extends Exception implements Alertable
{
    public function __construct()
    {
        parent::__construct('You are not authorized to perform this action.');
    }
}
