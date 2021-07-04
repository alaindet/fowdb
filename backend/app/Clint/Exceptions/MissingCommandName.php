<?php

namespace App\Clint\Exceptions;

class MissingCommandNameException extends ClintException
{
    public function __construct() {
        parent::__construct(
            "Missing command name. Please provide a valid command name."
        );
    }
}
