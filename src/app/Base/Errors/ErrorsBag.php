<?php

namespace App\Base\Errors;

use App\Base\Items\ItemsCollection;
use App\Base\Errors\Error;

class ErrorsBag extends ItemsCollection
{
    public function addError(string $message): ErrorsBag
    {
        $error = new Error();
        $error->message = $message;
        $this->add($error);
        return $this;
    }
}
