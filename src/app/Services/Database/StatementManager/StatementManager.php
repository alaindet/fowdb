<?php

namespace App\Services\Database\StatementManager;

use App\Services\Database\StatementManager\StatementCreateTrait;
use App\Services\Database\StatementManager\StatementReadTrait;
use App\Services\Database\StatementManager\StatementCombineTrait;
use App\Services\Database\StatementManager\StatementConvertTrait;

abstract class StatementManager
{
    use StatementCreateTrait;
    use StatementReadTrait;
    use StatementCombineTrait;
    use StatementConvertTrait;
}
