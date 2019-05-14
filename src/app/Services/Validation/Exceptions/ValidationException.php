<?php

namespace App\Services\Validation\Exceptions;

use App\Base\Exceptions\Exception;
use App\Base\Exceptions\Alertable;
use App\Base\Exceptions\Previousable;
use App\Base\Errors\ErrorsBag;

class ValidationException extends Exception implements
    Alertable,
    Previousable
{
    public $redirectTo = self::REDIRECT_BACK;

    public function __construct(ErrorsBag $errors)
    {
        if ($errors->count() === 1) {
            $this->message = $errors->first()->message;
        } else {
            $messages = $errors->pluck("message")->toArray();
            $this->message = "- ".implode("\n- ", $messages);
        }
    }
}
