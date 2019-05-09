<?php

namespace App\Services\Database\StatementManager;

use App\Services\Database\Interfaces\SqlStatementInterface;

/**
 * From App\Services\Database\StatementManager\StatementManager
 */
trait StatementConvertTrait
{
    static private $clauseBuilders = [
        "SELECT"      => "buildSelectClause",
        "FROM"        => "buildFromClause",
        "UPDATE"      => "buildUpdateClause",
        "SET"         => "buildSetClause",
        "INSERT INTO" => "buildInsertIntoClause",
        "VALUES"      => "buildValuesClause",
        "DELETE FROM" => "buildDeleteFromClause",
        "WHERE"       => "buildWhereClause",
        "GROUP BY"    => "buildGroupByClause",
        "HAVING"      => "buildHavingClause",
        "ORDER BY"    => "buildOrderByClause",
        "LIMIT"       => "buildLimitClause",
        "OFFSET"      => "buildOffsetClause",
    ];

    /**
     * Transforms every SQL clause array data as a proper SQL statement string
     * Clause builders MUST return NULL to skip adding the clause to the result
     *
     * @param SqlStatementInterface $statement
     * @return string SQL valid statement
     */
    static public function toString(SqlStatementInterface $statement): string
    {
        $sql = [];

        foreach ($statement->clauses as $name => $values) {
            $clauseBuilder = self::$clauseBuilders[$name];
            $clause = self::$clauseBuilder($values);
            if ($clause !== null) {
                $sql[] = $clause;
            }
        }

        return implode(" " , $sql);
    }

    static private function buildSelectClause(array $fields): string
    {
        if ($fields === []) {
            return "SELECT *";
        }

        $list = implode(", ", $fields);
        return "SELECT {$list}";
    }
    
    static private function buildFromClause(string $table): string
    {
        return "FROM {$table}";
    }

    static private function buildUpdateClause(string $table): string
    {
        return "UPDATE {$table}";
    }

    static private function buildSetClause(array $values): string
    {
        $assignments = [];
        foreach ($values as $column => $value) {
            $assignments[] = "{$column} = {$value}";
        }
        $assignmentsList = implode(", ", $assignments);
        return "SET {$assignmentsList}";
    }

    static private function buildInsertIntoClause(string $table): string
    {
        return "INSERT INTO {$table}";
    }

    static private function buildValuesClause(array $values): string
    {
        $fields = [];
        $vals = [];

        foreach ($values as $field => $value) {
            $fields[] = $field;
            $vals[]  = $value;
        }

        $fields = "(".implode(", ", $fields).")";
        $vals = "(".implode(", ", $vals).")";

        return "{$fields} VALUES {$vals}";
    }

    static private function buildDeleteFromClause(string $table): string
    {
        return "DELETE FROM {$table}";
    }

    static private function buildWhereClause(array $values): ?string
    {
        // Skip this clause if needed
        if ($values === []) {
            return null;
        }

        // A for loop is faster than any other loop in PHP!
        // https://github.com/alaindet/php-playground/blob/master/compare/loops.php
        $conditions = "";

        for ($i = 0, $len = count($values); $i < $len; $i++) {
            $operator = &$values[$i][0];
            $condition = &$values[$i][1];
            if ($i > 0) {
                $conditions .= " {$operator} {$condition}";
            } else {
                $conditions .= "{$condition}";
            }
        }

        return "WHERE {$conditions}";
    }

    static private function buildGroupByClause(array $values): ?string
    {
        // Skip this clause if needed
        if ($values === []) {
            return null;
        }

        $list = implode(", ", $values);
        return "GROUP BY {$list}";
    }

    static private function buildHavingClause(array $values): ?string
    {
        // Skip this clause if needed
        if ($values === []) {
            return null;
        }

        $conditions = "";
        for ($i = 0, $len = count($values); $i < $len; $i++) {
            $operator = &$values[$i][0];
            $condition = &$values[$i][1];
            if ($i > 0) {
                $conditions .= " {$operator} {$condition}";
            } else {
                $conditions .= "{$condition}";
            }
        }

        return "HAVING {$conditions}";
    }

    static private function buildOrderByClause(array $values): ?string
    {
        // Skip this clause if needed
        if ($values === []) {
            return null;
        }

        $sortings = implode(", ", $values);
        return "ORDER BY {$sortings}";
    }

    static private function buildLimitClause(int $value): ?string
    {
        if ($value === -1) {
            return null;
        }

        return "LIMIT {$value}";
    }

    static private function buildOffsetClause(int $value): ?string
    {
        if ($value === -1) {
            return null;
        }

        return "OFFSET {$value}";
    }
}
