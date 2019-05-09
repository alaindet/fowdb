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
     * Single-value clauses are left untouched unless $overrideOnSingleValue is true
     *
     * @param SqlStatementInterface $a
     * @param SqlStatementInterface $b
     * @param bool $overrideOnSingleValue
     * @return void
     */
    static public function mergeWith(
        SqlStatementInterface $a,
        SqlStatementInterface $b,
        bool $overrideOnSingleValue = false
    ): void
    {
        foreach ($b->clauses as $name => $bClause) {

            // Skip clauses which are not set
            if (!$b->isClauseSet($name)) {
                continue;
            }

            // Concatenate multi-value clauses
            if (gettype($bClause) === "array") {
                $a->clauses[$name] = array_merge($a->clauses[$name], $bClause);
                continue;
            }

            // Replace single-value clauses?
            if ($overrideOnSingleValue) {
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

            // Skip clauses which are not set
            if (!$b->isClauseSet($name)) {
                continue;
            }

            // Replace
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
