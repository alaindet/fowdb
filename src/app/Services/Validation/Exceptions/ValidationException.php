<?php

namespace App\Services\Validation\Exceptions;

use App\Base\Exceptions\Exception;
use App\Exceptions\Alertable;
use App\Exceptions\Previousable;
use App\Base\Errors\ErrorsBag;

class ValidationException extends Exception implements Alertable, Previousable
{
    public $redirectTo = 'back';

    public function __construct(ErrorsBag $errors)
    {
        if ($errors->count() === 1) {
            $this->message = $errors->first()->message;
        } else {
            $this->message = (
                "<ul class=\"display-inline-block\">".
                    "<li>".implode('</li><li>', $errors->toArray())."</li>".
                "</ul>"
            );
        }
    }
}
