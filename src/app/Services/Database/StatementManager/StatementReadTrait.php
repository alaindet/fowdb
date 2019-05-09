<?php

namespace App\Services\Database\StatementManager;

use App\Services\Database\Statement\SqlStatement;

/**
 * From App\Services\Database\StatementManager\StatementManager
 */
trait StatementReadTrait
{
    static public function isClauseSet(
        SqlStatement $statement,
        string $clause
    ): bool
    {
        if (!isset($statement->clauses[$clause])) {
            return false;
        }

        $defaultValues = [
            "array"   => [],
            "string"  => "",
            "integer" => -1,
        ];
        
        $clause = &$statement->clauses[$clause];
        $defaultValue = $defaultValues[gettype($clause)];

        return $clause !== $defaultValue;
    }
}
