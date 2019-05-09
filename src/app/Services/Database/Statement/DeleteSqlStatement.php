<?php

namespace App\Services\Database\Statement;

use App\Services\Database\Statement\SqlStatement;

/**
 * Reference: https://dev.mysql.com/doc/refman/8.0/en/delete.html
 */
class DeleteSqlStatement extends SqlStatement
{
    public $clauses = [
        "DELETE FROM" => "",
        "WHERE"       => [],
        "LIMIT"       => -1,
        "OFFSET"      => -1,
    ];

    /**
     * Set the table where to delete data from
     *
     * @param string $value The table name
     * @return DeleteSqlStatement
     */
    public function deleteFrom(string $value): DeleteSqlStatement
    {
        $this->clauses["DELETE FROM"] = $value;

        return $this;
    }

    /**
     * Alias for DeleteSqlStatement::deleteFrom
     *
     * @param string $value
     * @return DeleteSqlStatement
     */
    public function table(string $value): DeleteSqlStatement
    {
        return $this->deleteFrom($value);
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
     * @param $operator1 Possible values: "AND", "OR"
     * @param $operator2 Possible values: "AND", "OR"
     * @return DeleteSqlStatement
     */
    public function where(
        $conditions,
        string $operator1 = "AND",
        string $operator2 = "AND"
    ): DeleteSqlStatement
    {
        if (is_array($conditions)) {

            $conditions = "(".implode(" ".$operator1." ", $conditions).")";
            $this->clauses["WHERE"][] = [$operator2, $conditions];

        } else {

            $this->clauses["WHERE"][] = [$operator1, $conditions];

        }

        return $this;
    }

    /**
     * Adds a sorting criteria to the ORDER BY clause
     *
     * @param string|array $conditions
     * @return DeleteSqlStatement
     */
    public function orderBy($columns): DeleteSqlStatement
    {
        $clause =& $this->clauses["ORDER BY"];
        if (!is_array($columns)) $clause[] = $columns;
        else $clause = array_merge($clause, $columns);

        return $this;
    }

    /**
     * Sets the row count of the LIMIT clause
     *
     * @param integer $limit
     * @return DeleteSqlStatement
     */
    public function limit(int $limit): DeleteSqlStatement
    {
        $this->clauses["LIMIT"] = $limit;

        return $this;
    }
}
