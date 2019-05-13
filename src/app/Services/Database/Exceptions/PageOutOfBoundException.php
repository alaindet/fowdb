<?php

namespace App\Services\Database\Exceptions;

use App\Services\Database\Exceptions\DatabaseException;
use App\Base\Exceptions\Alertable;

class PageOutOfBoundException extends DatabaseException implements Alertable
{
    public $redirectTo = self::REDIRECT_BACK;
}
