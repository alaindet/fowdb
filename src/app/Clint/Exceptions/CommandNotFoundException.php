<?php

namespace App\Clint\Exceptions;

class CommandNotFoundException extends ClintException
{
    public function __construct(string $commandName)
    {
        parent::__construct(
            "Invalid command {$commandName}\n".
            "Run \"php cli/clint list\" to explore the commands"
        );
    }
}
