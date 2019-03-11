<?php

namespace App\Services\Lookup\Exceptions;

use App\Services\Lookup\Exceptions\LookupException;

class MissingDataException extends LookupException
{
    public function __construct(string $path)
    {
        $this->message("No data exists for path \"{$path}\".");
    }
}
