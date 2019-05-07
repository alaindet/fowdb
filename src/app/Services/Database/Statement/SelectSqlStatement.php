<?php

namespace App\Services\Database\Statement;

use App\Services\Database\Statement\SqlStatement;
use App\Services\Database\Statement\Exceptions\RequiredClauseException;

/**
 * https://dev.mysql.com/doc/refman/8.0/en/select.html
 */
class SelectSqlStatement extends SqlStatement
{
    private $aux = [];

    public $clauses = [
        "SELECT" => [],
        "FROM" => "",
        "WHERE" => [],
        "GROUP BY" => [],
        "HAVING" => [],
        "ORDER BY" => [],
        "LIMIT" => -1,
        "OFFSET" => -1,
    ];

    /**
     * Appends a select expression to the SELECT clause
     * A select expression can be as simple as "id" or
     * More complex like "cards.name as c_name"
     * 
     * @param string|string[] $expressions The select expression(s)
     * @return SelectSqlStatement
     */
    public function select($expressions): SelectSqlStatement
    {
        $clause =& $this->clauses["SELECT"];

        if (!is_array($expressions)) $clause[] = $expressions;
        else $clause = array_merge($clause, $expressions);

        return $this;
    }

    /**
     * Alias for SelectSqlStatament::select()
     *
     * @param string|string[] $expressions The select expression(s)
     * @return SelectSqlStatement
     */
    public function fields($expressions): SelectSqlStatement
    {
        return $this->select($expressions);
    }

    /**
     * Resets all previous SELECT expressions
     *
     * @return SelectSqlStatement
     */
    public function resetSelect(): SelectSqlStatement
    {
        $this->clauses["SELECT"] = [];

        return $this;
    }

    /**
     * Sets the table_reference expression
     * For INNER JOIN expressions, better use SelectSqlStatement::innerJoin()
     *
     * @param string $table Table name
     * @param string $alias Table alias
     * @return SelectSqlStatement
     */
    public function from(
        string $table,
        string $alias = null
    ): SelectSqlStatement
    {
        $this->aux["table"] = $table;
        $this->aux["table-alias"] = $table;
        $expression = $table;
        
        if ($alias !== null) {
            $this->aux["table-alias"] = $alias;
            $expression .= " as {$alias}";
        }

        $this->clauses["FROM"] = $expression;

        return $this;
    }

    /**
     * Adds an INNER JOIN table
     * 
     * If first argument is a string, it"s the join table name,
     * If first argument is an array, then it"s [join_table_name, alias]
     * 
     * Implicit operator (=)
     * ->innerJoin(["rarities","r"], "id", "rarity")
     * 
     * Explicit operator
     * ->innerJoin(["rarities","r"], "id", "=", "rarity")
     * ->innerJoin(["rarities","r"], "id", "<>", "rarity")
     *
     * @param string|string[] $rTable Table name (and alias)
     * @param string $rField
     * @param string $operator
     * @param string $lField
     * @return SelectSqlStatement
     */
    public function innerJoin(
        $rTable, // string|string[]
        string $rField,
        string $operator,
        string $lField = null
    ): SelectSqlStatement
    {
        // ERROR: Missing base table
        if (!isset($this->aux["table"])) {
            throw new RequiredClauseException(
                "Method \"from\" must be called before \"innerJoin\""
            );
        }

        // Left table
        $lAlias = $this->aux["table-alias"];

        // Right table
        if (is_array($rTable)) {
            [$rTable, $rAlias] = $rTable;
            $rTableRef = "{$rTable} as {$rAlias}";
        } else {
            [$rTable, $rAlias] = [$rTable, $rTable];
            $rTableRef = $rTable;
        }

        // Implicit = operator
        if ($lField === null) {
            $lField = $operator;
            $operator = "=";
        }

        // Add to the FROM clause
        $this->clauses["FROM"] .= (
            " INNER JOIN {$rTableRef} ON ".
            "{$rAlias}.{$rField} {$operator} {$lAlias}.{$lField}"
        );

        $this->aux["table-alias"] = $rAlias;

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
     * @param string|string[] $conditions
     * @param $operator1 Possible values: "AND", "OR"
     * @param $operator2 Possible values: "AND", "OR"
     * @return SelectSqlStatement
     */
    public function where(
        $conditions,
        string $operator1 = "AND",
        string $operator2 = "AND"
    ): SelectSqlStatement
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
     * Appens an expression to the GROUP BY clause
     * 
     * @param string|string[] $expressions The select expression(s)
     * @return SelectSqlStatement
     */
    public function groupBy($expressions): SelectSqlStatement
    {
        $clause =& $this->clauses["GROUP BY"];

        if (!is_array($expressions)) $clause[] = $expressions;
        else $clause = array_merge($clause, $expressions);

        return $this;
    }

    /**
     * Adds a filtering condition to the HAVING clause
     * By default, this condition is chained to the previous, if existing,  via
     * an AND operator. Possible operators: and, or
     * 
     * If $conditions is an array, $operator1 glues conditions together in a
     * string and $operator2 glues that string to the previous conditions
     * 
     * If $condition is a string, $operator1 glues it to the previous conditions
     * 
     * @param string|string[] $conditions
     * @param $operator1 Possible values: "AND", "OR"
     * @param $operator2 Possible values: "AND", "OR"
     * @return SelectSqlStatement
     */
    public function having(
        $conditions,
        string $operator1 = "AND",
        string $operator2 = "AND"
    ): SelectSqlStatement
    {
        if (is_array($conditions)) {

            $conditions = "(".implode(" {$operator1} ", $conditions).")";
            $this->clauses["HAVING"][] = [$operator2, $conditions];

        } else {

            $this->clauses["HAVING"][] = [$operator1, $conditions];

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
        $clause =& $this->clauses["ORDER BY"];
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
    public function limit(int $limit): SelectSqlStatement
    {
        $this->clauses["LIMIT"] = $limit;
        return $this;
    }

    public function resetLimit(): SelectSqlStatement
    {
        $this->clauses["LIMIT"] = -1;
        return $this;
    }

    /**
     * Sets the offset of the OFFSET clause
     *
     * @param integer $offset
     * @return SelectSqlStatement
     */
    public function offset(int $offset): SelectSqlStatement
    {
        $this->clauses["OFFSET"] = $offset;
        return $this;
    }

    public function resetOffset(): SelectSqlStatement
    {
        $this->clauses["OFFSET"] = -1;
        return $this;
    }
}
