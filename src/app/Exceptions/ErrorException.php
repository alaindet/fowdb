<?php

namespace App\Exceptions;

use App\Base\Exception;

class ErrorException extends Exception
{
    public function __construct(
        string $message,
        int $level,
        string $file,
        int $line
    )
    {
        parent::__construct($message, $level);
        $this->file = $file;
        $this->line = $line;
    }
}
