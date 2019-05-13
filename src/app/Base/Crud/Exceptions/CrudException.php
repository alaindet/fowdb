<?php

namespace App\Base\Crud\Exceptions;

use App\Base\Exceptions\Exception;
use App\Base\Exceptions\Alertable;
use App\Base\Exceptions\Previousable;

class CrudException extends Exception implements Alertable, Previousable
{
    public $redirectTo = self::REDIRECT_BACK;
}
