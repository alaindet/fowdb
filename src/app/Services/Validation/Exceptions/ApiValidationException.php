<?php

namespace App\Services\Validation\Exceptions;

use App\Base\Exceptions\ApiException;
use App\Base\Errors\ErrorsBag;

class ApiValidationException extends ApiException
{
    public function __construct(ErrorsBag $errors)
    {
        $this->message = implode("\n", $errors->pluck('message')->toArray());
    }
}
