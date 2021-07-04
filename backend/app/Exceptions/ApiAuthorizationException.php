<?php

namespace App\Exceptions;

use App\Base\Exception;
use App\Exceptions\Jsonable;

class ApiAuthorizationException extends Exception implements Jsonable
{
    public function __construct()
    {
        parent::__construct('You are not authorized to perform this action.');
    }
}
