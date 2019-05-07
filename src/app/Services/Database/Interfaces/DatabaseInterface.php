<?php

namespace App\Services\Database\Interfaces;

use App\Services\Database\Statement\DeleteSqlStatement;
use App\Services\Database\Statement\InsertSqlStatement;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Services\Database\Statement\UpdateSqlStatement;

interface DatabaseInterface
{
    public function create(InsertSqlStatement $statement): DatabaseInterface;
    public function read(SelectSqlStatement $statement): DatabaseInterface;
    public function update(UpdateSqlStatement $statement): DatabaseInterface;
    public function delete(DeleteSqlStatement $statement): DatabaseInterface;

    public function bind(array $values): DatabaseInterface;
    public function execute(): DatabaseInterface;
    public function get(string $className = null); // array|Entity
    public function count(string $field = null): int;

    public function first(string $className = null); // array|Entity
    public function resetAutoIncrement(string $table): DatabaseInterface;

    public function rawSelect(string $sql): array;
    public function rawCount(string $table, string $condition, string $field): int;
}
