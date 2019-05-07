<?php

namespace App\Services\Database\Interfaces;

use App\Services\Database\Statement\SelectSqlStatement;

interface PaginatorInterface
{
    // Setters
    public function setStatement(SelectSqlStatement $statement): PaginatorInterface;
    public function setPage(int $page): PaginatorInterface;
    public function setResultsPerPage(int $perPage): PaginatorInterface;
    public function setLink(string $link): PaginatorInterface;
    public function setQueryParameter(string $param): PaginatorInterface;

    public function fetch(string $className = null): PaginatorInterface;
    public function getResults(); // array|ItemsCollection
    public function getPaginationData(): object;
}
