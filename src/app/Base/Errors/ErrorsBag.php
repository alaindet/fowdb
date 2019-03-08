<?php

namespace App\Errors;

use App\Base\Items\ItemsCollection;
use App\Errors\Error;

class ErrorsBag extends ItemsCollection
{
    public function add(string $message): ErrorsBag
    {
        $error = new Error();
        $error->message = $message;
        $this->add($error);
        return $this;
    }

    public function get(): array
    {
        return $this->toArray();
    }
}
