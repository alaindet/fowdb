<?php

namespace App\Clint\Exceptions;

class MissingDescriptionsException extends ClintException
{
    public function __construct() {
        parent::__construct(
            "Missing descriptions/_all.md file"
        );
    }
}
