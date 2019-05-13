<?php

namespace App\Models\Exceptions;

use App\Base\Exceptions\Exception;
use App\Base\Exceptions\Alertable;

class CardModelException extends Exception implements Alertable
{
    public $redirectTo = self::REDIRECT_BACK;
}
