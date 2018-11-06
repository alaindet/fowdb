<?php

namespace App\Exceptions;

use App\Base\Exception;
use App\Exceptions\Alertable;

class ValidationException extends Exception implements Alertable
{
    public $redirectTo = 'back';
}
