<?php

namespace App\Services\Database\StatementManager;

use App\Services\Database\Interfaces\SqlStatementInterface;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Services\Database\Statement\InsertSqlStatement;
use App\Services\Database\Statement\UpdateSqlStatement;
use App\Services\Database\Statement\DeleteSqlStatement;


trait StatementCreateTrait
{
    static private $types = [
        "insert" => InsertSqlStatement::class,
        "select" => SelectSqlStatement::class,
        "update" => UpdateSqlStatement::class,
        "delete" => DeleteSqlStatement::class,
    ];

    static public function new(string $type): SqlStatementInterface
    {
        $statementClass = self::$types[$type];
        return new $statementClass();
    }
}
