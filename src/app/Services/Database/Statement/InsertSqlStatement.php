<?php

namespace App\Services\Database\Statement;

use App\Services\Database\Statement\SqlStatement;

/**
 * Reference: https://dev.mysql.com/doc/refman/8.0/en/insert.html
 */
class InsertSqlStatement extends SqlStatement
{
    public $clauses = [
        "INSERT INTO" => "",
        "VALUES"      => [],
    ];

    /**
     * Set the table where to put data
     *
     * @param string $value The table name
     * @return InsertSqlStatement
     */
    public function into(string $value): InsertSqlStatement
    {
        $this->clauses["INSERT INTO"] = $value;

        return $this;
    }

    /**
     * Alias for InsertSqlStatement::into
     *
     * @param string $value
     * @return InsertSqlStatement
     */
    public function table(string $value): InsertSqlStatement
    {
        return $this->into($value);
    }

    /**
     * Adds values to insert. Input array must be an associative array
     * column => value
     *
     * @param array $values
     * @return InsertSqlStatement
     */
    public function values(array $values): InsertSqlStatement
    {
        foreach ($values as $column => $value) {
            $this->clauses["VALUES"][$column] = $value;
        }

        return $this;
    }
}
