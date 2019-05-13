<?php

namespace App\Legacy\Exceptions;

use App\Base\Exceptions\Exception;
use App\Base\Exceptions\Alertable;

class AuthorizationException extends Exception implements Alertable
{
    public function __construct()
    {
        parent::__construct(
            "You are not authorized to perform this action."
        );
    }
}
