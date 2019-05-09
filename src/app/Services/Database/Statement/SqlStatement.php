<?php

namespace App\Services\Database\Statement;

use App\Services\Database\Interfaces\SqlStatementInterface;
use App\Services\Database\StatementManager\StatementManager;

/**
 * This is the base class for SqlStatement objects
 * 
 * Define these in child class
 * protected $clauses;
 */
abstract class SqlStatement implements SqlStatementInterface
{
    /**
     * Map of all SQL clauses and their temporary value
     * 
     * key: SQL clause (Ex.: "ORDER BY")
     * value: The temporary value (later used when building the SQL string)
     * 
     * The value can be a multi-value (array) or a single-value (string|integer)
     * 
     * Ex.: Multi-value: SELECT, WHERE, ORDER BY
     * Ex.: Single-value: FROM, LIMIT, OFFSET
     * 
     * Re-define this property in child class to init clauses and their value
     *
     * @var array
     */
    protected $clauses = [
        // Ex.: "SELECT" => [],
        // Ex.: "FROM" => "",
    ];

    private $boundValues = [
        // ":id" => 123,
        // ":name" => "Alain",
    ];

    public function setBoundValues(array $boundValues): SqlStatementInterface
    {
        $this->boundValues = $boundValues;
        return $this;
    }

    public function getBoundValues(): array
    {
        return $this->boundValues;
    }

    public function toString(): string
    {
        return StatementManager::toString($this);
    }


    /**
     * Merges this statement with a provided one
     * If $fromBOnSingleValue is TRUE, uses 
     *
     * @param SqlStatementInterface $statement
     * @param bool $fromBOnSingleValue
     * @return SqlStatementInterface
     */
    public function mergeWith(
        SqlStatementInterface $statement,
        bool $fromBOnSingleValue = false
    ): SqlStatementInterface
    {
        StatementManager::mergeWith($this, $statement, $fromBOnSingleValue);
        return $this;
    }

    public function replaceWith(
        SqlStatementInterface $statement
    ): SqlStatementInterface
    {
        StatementManager::replaceWith($this, $statement);
        return $this;
    }

    /**
     * Returns the final valid SQL statement as a string
     * Calls the only public method of the Stringable trait
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }
}
