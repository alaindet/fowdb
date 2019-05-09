<?php

namespace App\Services\Database\StatementManager;

use App\Services\Database\StatementManager\StatementCreateTrait;
use App\Services\Database\StatementManager\StatementConvertTrait;
use App\Services\Database\StatementManager\StatementCombineTrait;

abstract class StatementManager
{
    use StatementCreateTrait;
    use StatementConvertTrait;
    use StatementCombineTrait;
}
