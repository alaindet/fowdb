<?php

namespace App\Views\Component\Exceptions;

use App\Views\Component\Exception\ComponentException;

class ComponentNotFoundException extends ComponentException
{
    public function __construct(string $name)
    {
        parent::__construct(
            "Component with name *{$name}* not found"
        );
    }
}
