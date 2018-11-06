<?php

namespace App\Services\Database\Statement;

use App\Services\Database\Statement\SqlStatement;

/**
 * TO DO: groupBy(), having()
 * 
 * Reference: https://dev.mysql.com/doc/refman/8.0/en/select.html
 */
class SelectSqlStatement extends SqlStatement
{
    public $clauses = [
        'SELECT' => [],
        'FROM' => '',
        'WHERE' => [],
        // 'GROUP BY' => [],
        // 'HAVING' => [],
        'ORDER BY' => [],
        'LIMIT' => -1,
        'OFFSET' => -1,
    ];

    /**
     * Appends a select expression to the SELECT clause
     * A select expression can be as simple as 'id' or
     * More complex like 'cards.name as c_name'
     * 
     * @param string|array $expr The select expression(s)
     * @return SelectSqlStatement
     */
    public function select($expressions): SelectSqlStatement
    {
        $clause =& $this->clauses['SELECT'];

        if (!is_array($expressions)) $clause[] = $expressions;
        else $clause = array_merge($clause, $expressions);

        return $this;
    }

    /**
     * Resets all previous SELECT expressions
     *
     * @return SelectSqlStatement
     */
    public function resetSelect(): SelectSqlStatement
    {
        $this->clauses['SELECT'] = [];

        return $this;
    }

    /**
     * Sets the table_references expression ('cards', 'cards INNER JOIN rulings...')
     *
     * @param string $table
     * @return SelectSqlStatement
     */
    public function from(string $table): SelectSqlStatement
    {
        $this->clauses['FROM'] = $table;

        return $this;
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
     * @return SelectSqlStatement
     */
    public function where(
        $conditions,
        string $operator1 = 'AND',
        string $operator2 = 'AND'
    ): SelectSqlStatement
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
     * @return SelectSqlStatement
     */
    public function orderBy($columns): SelectSqlStatement
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
     * @return SelectSqlStatement
     */
    public function limit(int $limit, bool $add = false): SelectSqlStatement
    {
        if (!$add) {
            
            $this->clauses['LIMIT'] = $limit;

        } else {

            $this->clauses['LIMIT'] += $limit;

        }

        return $this;
    }

    /**
     * Sets the offset of the OFFSET clause
     *
     * @param integer $offset
     * @return SelectSqlStatement
     */
    public function offset(int $offset, bool $add = false): SelectSqlStatement
    {
        if (!$add) {

            $this->clauses['OFFSET'] = $offset;

        } else {

            $this->clauses['OFFSET'] += $offset;

        }

        return $this;
    }
}
