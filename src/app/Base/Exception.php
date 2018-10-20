<?php

namespace App\Base;

use Exception as BuiltinException;
use App\Base\BaseTrait;

abstract class Exception extends BuiltinException
{
    use BaseTrait;
}
