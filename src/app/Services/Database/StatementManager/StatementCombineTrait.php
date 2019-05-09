<?php

namespace App\Services\Database\StatementManager;

use App\Services\Database\Interfaces\SqlStatementInterface;
use App\Utils\Objects;

/**
 * From App\Services\Database\StatementManager\StatementManager
 */
trait StatementCombineTrait
{
    /**
     * Merges two statements $a and $b together, by altering $a
     * 
     * Multi-value clauses are concatenated ($a clause before $b clause)
     * Single-value clauses are left untouched unless $fromBOnSingleValue is true
     *
     * @param SqlStatementInterface $a
     * @param SqlStatementInterface $b
     * @param bool $fromBOnSingleValue
     * @return void
     */
    static public function mergeWith(
        SqlStatementInterface $a,
        SqlStatementInterface $b,
        bool $fromBOnSingleValue = false
    ): void
    {
        foreach ($b->clauses as $name => $bClause) {
            if (gettype($bClause) === "array") { // Concatenate clauses
                $a->clauses[$name] = array_merge($a->clauses[$name], $bClause);
                continue;
            }
            if ($fromBOnSingleValue) { // Replace clause
                $a->clauses[$name] = $bClause;
            }
        }
    }

    /**
     * Merges two statements together,
     * returns a new statement
     *
     * @param SqlStatementInterface $a
     * @param SqlStatementInterface $b
     * @param bool $fromBOnSingleValue
     * @return SqlStatementInterface
     */
    static public function merge(
        SqlStatementInterface $a,
        SqlStatementInterface $b,
        bool $fromBOnSingleValue = false
    ): SqlStatementInterface
    {
        $new = Objects::clone($a);
        self::mergeWith($new, $b, $fromBOnSingleValue);
        return $new;
    }

    /**
     * Replaces $a clauses with all existing clauses in $b
     *
     * @param SqlStatementInterface $a
     * @param SqlStatementInterface $b
     * @return void
     */
    static public function replaceWith(
        SqlStatementInterface $a,
        SqlStatementInterface $b
    ): void
    {
        foreach ($b->clauses as $name => $value) {
            $a->clauses[$name] = $value;
        }
    }

    /**
     * Replaces $a clauses with all existing clauses in $b,
     * returns a new statement
     *
     * @param SqlStatementInterface $a
     * @param SqlStatementInterface $b
     * @return SqlStatementInterface
     */
    static public function replace(
        SqlStatementInterface $a,
        SqlStatementInterface $b
    ): SqlStatementInterface
    {
        $new = Objects::clone($a);
        self::replaceWith($new, $b);
        return $new;
    }
}
