<?php

namespace App\Clint\Exceptions;

class DuplicateCommandException extends ClintException
{
    public function __construct(string $command)
    {
        parent::__construct(
            "Command \"{$command}\" already exists! Please run this to check all existing commands\n\n\$ php clint list"
        );
    }
}
