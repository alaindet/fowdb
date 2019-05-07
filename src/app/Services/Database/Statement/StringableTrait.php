<?php

namespace App\Services\Database\Statement;

/**
 * Can only be used by its base class:
 * App\Services\Database\Statement\SqlStatement
 */
trait StringableTrait
{
    private $clausesToStrings = [
        'SELECT' => 'selectClauseToString',
        'FROM' => 'fromClauseToString',
        'UPDATE' => 'updateClauseToString',
        'SET' => 'setClauseToString',
        'INSERT INTO' => 'insertIntoClauseToString',
        'VALUES' => 'valuesClauseToString',
        'DELETE FROM' => 'deleteFromClauseToString',
        'WHERE' => 'whereClauseToString',
        'GROUP BY' => 'groupByClauseToString',
        'HAVING' => 'havingClauseToString',
        'ORDER BY' => 'orderByClauseToString',
        'LIMIT' => 'limitClauseToString',
        'OFFSET' => 'offsetClauseToString',
    ];

    /**
     * Builds the final SQL string. The only public method
     * Reads data from base class SqlStatement
     *
     * @param array $clauses
     * @return string Final SQL
     */
    public function ToString(): string
    {
        $sql = [];

        // Check the values inside the clause functions
        // Because some clauses might be required and MUST output something
        // anyway, like SELECT *
        // If $values are not set and clause is not required, '' is returned
        foreach ($this->clauses as $name => $values) {
            $function = $this->clausesToStrings[$name];
            $clause = $this->$function($name, $values);
            if ($clause !== '') $sql[] = $clause;
        }

        return implode(' ', $sql);
    }

    /**
     * Required clause
     *
     * @param string $name SELECT
     * @param array $values The select expressions, like 'c.name as card_name'
     * @return string
     */
    private function selectClauseToString(string $name, array $values): string
    {
        if (empty($values)) $value = '*';
        else $value = implode(', ', $values);

        return "{$name} {$value}";
    }

    /**
     * Required clause, requires: SELECT clause
     *
     * @param string $name FROM
     * @param string $value The table name
     * @return string
     */
    private function fromClauseToString(string $name, string $value): string
    {
        return "{$name} {$value}";
    }

    /**
     * Required clause
     *
     * @param string $name UPDATE
     * @param string $value The table name
     * @return string
     */
    private function updateClauseToString(string $name, string $value): string
    {
        return "{$name} {$value}";
    }

    /**
     * Required clause, requires: UPDATE clause
     *
     * @param string $name SET
     * @param array $values The values to set
     * @return string
     */
    private function setClauseToString(string $name, array $values): string
    {
        $assignments = [];

        foreach ($values as $column => $value) {
            $assignments[] = "{$column} = {$value}";
        }

        return 'SET '.implode(', ', $assignments);
    }

    /**
     * Required clause
     *
     * @param string $name INSERT INTO
     * @param string $value The table name
     * @return string
     */
    private function insertIntoClauseToString(
        string $name,
        string $value
    ): string
    {
        return "{$name} {$value}";
    }

    /**
     * Required clause, requires: INSERT INTO clause
     *
     * @param string $name
     * @param array $values
     * @return string
     */
    private function valuesClauseToString(string $name, array $values): string
    {
        $columns = [];
        $vals = [];

        foreach ($values as $column => $value) {
            $columns[] = $column;
            $vals[]  = $value;
        }

        $columns = '('.implode(', ', $columns).')';
        $vals = '('.implode(', ', $vals).')';
        return "{$columns} VALUES {$vals}";
    }

    /**
     * Required clause
     *
     * @param string $name DELETE FROM
     * @param string $value The table name
     * @return string
     */
    private function deleteFromClauseToString(
        string $name,
        string $value
    ): string
    {
        return "{$name} {$value}";
    }

    /**
     * Optional clause
     *
     * @param string $name WHERE
     * @param array $values Any set of valid where expressions, ex.: 'id = :id'
     * @return string
     */
    private function whereClauseToString(string $name, array $values): string
    {
        // Optional clause
        if (empty($values)) return '';

        // A for loop is faster than any other loop in PHP
        $value = '';

        for ($i = 0, $len = count($values); $i < $len; $i++) {

            $operator = &$values[$i][0];
            $condition = &$values[$i][1];

            if ($i > 0) $value .= " {$operator} {$condition}";
            else $value .= "{$condition}";

        }

        return "{$name} {$value}";
    }

    /**
     * Optional clause
     *
     * @param string $name GROUP BY
     * @param array $values Any set of valid GROUP BY expressions
     * @return string
     */
    private function groupByClauseToString(string $name, array $values): string
    {
        // Optional clause
        if (empty($values)) return '';

        $value = implode(', ', $values);
        return "{$name} {$value}";
    }

    /**
     * Optional clause
     * Identical to WHERE, but it's used to filter grouped values by GROUP BY
     *
     * @param string $name HAVING
     * @param array $values Any set of valid HAVING expressions
     * @return string
     */
    private function havingClauseToString(string $name, array $values): string
    {
        // Optional clause
        if (empty($values)) return '';

        // A for loop is faster than any other loop in PHP
        $value = '';
        for ($i = 0, $len = count($values); $i < $len; $i++) {

            $operator = &$values[$i][0];
            $condition = &$values[$i][1];

            if ($i > 0) $value .= " {$operator} {$condition}";
            else $value .= "{$condition}";

        }

        return "{$name} {$value}";
    }

    /**
     * Optional clause
     *
     * @param string $name ORDER BY
     * @param array $values Any set of valid sorting expressions, ex.: 'id DESC'
     * @return string
     */
    private function orderByClauseToString(string $name, array $values): string
    {
        // Optional clause
        if (empty($values)) return '';

        $value = implode(', ', $values);
        return "{$name} {$value}";
    }

    /**
     * Optional clause
     *
     * @param string $name LIMIT
     * @param integer $value Number of rows of the results table
     * @return string
     */
    private function limitClauseToString(string $name, int $value): string
    {
        return ($value !== -1) ? "{$name} {$value}" : "";
    }

    /**
     * Optional clause
     *
     * @param string $name OFFSET
     * @param integer $value Number of rows to skip (they're reaad anyway)
     * @return string
     */
    private function offsetClauseToString(string $name, int $value): string
    {
        return ($value !== -1) ? "{$name} {$value}" : "";
    }
}
