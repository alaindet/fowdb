<?php

namespace App\Clint\Exceptions;

class DescriptionNotFoundException extends ClintException
{
    public function __construct(string $command)
    {
        parent::__construct("Description for command \"{$command}\" not found");
    }
}
