<?php

namespace App\Services\Database\Exceptions;

use App\Services\Database\Exceptions\DatabaseException;
use App\Exceptions\Alertable;

class PageOutOfBoundException extends DatabaseException implements Alertable
{
    public $redirectTo = "back";
}
