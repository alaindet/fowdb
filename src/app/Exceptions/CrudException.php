<?php

namespace App\Exceptions;

use App\Base\Exception;
use App\Exceptions\Alertable;
use App\Exceptions\Previousable;

class CrudException extends Exception implements Alertable, Previousable
{
    public $redirectTo = 'back';
}
