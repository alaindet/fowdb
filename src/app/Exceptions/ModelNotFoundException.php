<?php

namespace App\Exceptions;

use App\Exceptions\ModelException;
use App\Exceptions\Alertable;

class ModelNotFoundException extends ModelException implements Alertable
{
    //
}
