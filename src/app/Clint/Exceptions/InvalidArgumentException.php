<?php

namespace App\Clint\Exceptions;

class InvalidArgumentException extends ClintException
{
    public function __construct() {
        parent::__construct(
            "Invalid argument for this command.\n" .
            "Run for example \"php clint help foo\" ".
            "to learn about the \"foo\" command"
        );
    }
}
