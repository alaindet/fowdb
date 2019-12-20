<?php

namespace App\Services\Database\Statement;

use App\Services\Database\Statement\SqlStatement;

/**
 * Reference: https://dev.mysql.com/doc/refman/8.0/en/update.html
 */
class UpdateSqlStatement extends SqlStatement
{
    public $clauses = [
        'UPDATE' => '',
        'SET' => [],
        'WHERE' => [],
        'ORDER BY' => [],
        'LIMIT' => -1,
    ];

    /**
     * Set the table where to update data into
     *
     * @param string $value The table name
     * @return UpdateSqlStatement
     */
    public function update(string $value): UpdateSqlStatement
    {
        $this->clauses['UPDATE'] = $value;

        return $this;
    }

    /**
     * Alias for UpdateSqlStatement::update
     *
     * @param string $value
     * @return UpdateSqlStatement
     */
    public function table(string $value): UpdateSqlStatement
    {
        return $this->update($value);
    }

    /**
     * Adds values to update. Input array must be an associative array
     * column => name
     *
     * @param array $values
     * @return UpdateSqlStatement
     */
    public function set(array $values): UpdateSqlStatement
    {
        foreach ($values as $column => $value) {
            $this->clauses['SET'][$column] = $value;
        }

        return $this;
    }

    /**
     * Adds values to update. Input array must be an associative array
     * column => name
     *
     * @param array $values
     * @return UpdateSqlStatement
     */
    public function values(array $values): UpdateSqlStatement
    {
        return $this->set($values);
    }

    /**
     * Adds a filtering condition to the WHERE clause
     * By default, this condition is chained to the previous, if existing,  via
     * an AND operator. Possible operators: and, or
     * 
     * If $conditions is an array, $operator1 glues conditions together in a
     * string and $operator2 glues that string to the previous conditions
     * 
     * If $condition is a string, $operator1 glues it to the previous conditions
     * 
     * @param string|array $conditions
     * @param $operator1 Possible values: 'AND', 'OR'
     * @param $operator2 Possible values: 'AND', 'OR'
     * @return UpdateSqlStatement
     */
    public function where(
        $conditions,
        string $operator1 = 'AND',
        string $operator2 = 'AND'
    ): UpdateSqlStatement
    {
        if (is_array($conditions)) {

            $conditions = '('.implode(' '.$operator1.' ', $conditions).')';
            $this->clauses['WHERE'][] = [$operator2, $conditions];

        } else {

            $this->clauses['WHERE'][] = [$operator1, $conditions];

        }

        return $this;
    }

    /**
     * Adds a sorting criteria to the ORDER BY clause
     *
     * @param string|array $conditions
     * @return UpdateSqlStatement
     */
    public function orderBy($columns): UpdateSqlStatement
    {
        $clause =& $this->clauses['ORDER BY'];
        if (!is_array($columns)) $clause[] = $columns;
        else $clause = array_merge($clause, $columns);

        return $this;
    }

    /**
     * Sets the row count of the LIMIT clause
     *
     * @param integer $limit
     * @return UpdateSqlStatement
     */
    public function limit(int $limit): UpdateSqlStatement
    {
        $this->clauses['LIMIT'] = $limit;

        return $this;
    }
}
