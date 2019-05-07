<?php

namespace App\Services\Database;

use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Utils\Uri;
use App\Services\Configuration\Configuration;
use App\Services\Database\Exceptions\PageOutOfBoundException;
use App\Services\Database\Interfaces\PaginatorInterface;

/**
 * Accepts a SelectSqlStatement and adjusts it to return a slice of the results
 */
class Paginator implements PaginatorInterface
{
    // Injected
    private $dbService;
    private $configService;

    // Input
    private $statement;
    private $page = 1;
    private $perPage;
    private $link;
    private $queryParam = "page";

    // Computed
    private $totalCount;
    private $count;
    private $lastPage;
    private $hasMorePages;
    private $lowerBound;
    private $upperBound;
    private $results;

    public function __construct()
    {
        $this->dbService = Database::getInstance();
        $this->configService = Configuration::getInstance();
    }

    public function setStatement(SelectSqlStatement $statement): PaginatorInterface
    {
        $this->statement = $statement;
        return $this;
    }

    public function setPage(int $page): PaginatorInterface
    {
        $this->page = $page;
        return $this;
    }

    public function setResultsPerPage(int $perPage): PaginatorInterface
    {
        $this->perPage = $perPage;
        return $this;
    }

    public function setLink(string $link): PaginatorInterface
    {
        $this->link = $link;
        return $this;
    }

    public function setQueryParameter(string $param): PaginatorInterface
    {
        $this->queryParam = $param;
        return $this;
    }

    public function fetch(string $className = null): PaginatorInterface
    {
        // ERROR: Page out of bound
        if ($this->page < 1) {
            throw new PageOutOfBoundException(
                "Page {$this->page} out of bound ".
                "(must be 1 or greater)"
            );
        }

        if ($this->perPage === null) {
            $this->perPage = $this->configService->get("db.resuts.limit");
        }

        $this->dbService->select($this->statement);
        $this->dbService->bind($this->statement->getBoundValues());
        $this->totalCount = $this->dbService->count(); // Queries the database
        $this->lastPage = intval(ceil($this->totalCount / $this->perPage));

        // ERROR: Page out of bound
        if ($this->page > $this->lastPage) {
            throw new PageOutOfBoundException(
                "Page {$this->page} out of bound ".
                "(must be {$this->lastPage} or less)"
            );
        }

        $this->statement->limit($this->buildSqlLimitClause());
        $this->statement->offset($this->buildSqlOffsetClause());
        $this->link = $this->buildPaginationLink();
        $this->results = $this->dbService->get($className);

        // Results is a collection
        if (isset($className)) {
            $this->count = $this->results->count();
            $this->hasMorePages = $this->count > $this->perPage;
            if ($this->hasMorePages) {
                $this->results->pop();
            }
        }

        // Results is array
        else {
            $this->count = count($results);
            $this->hasMorePages = $this->count > $this->perPage;
            if ($this->hasMorePages) {
                array_pop($this->results);
            }
        }

        return $this;
    }

    /**
     * Returns previously fetched results as array|ItemsCollection
     *
     * @return array|ItemsCollection
     */
    public function getResults()
    {
        return $this->results;
    }

    public function getPaginationData(): object
    {
        return (object) [
            "totalCount" => $this->totalCount,
            "count" => $this->upperBound - $this->lowerBound + 1,
            "page" => $this->page,
            "perPage" => $this->perPage,
            "lastPage" => $this->lastPage,
            "lowerBound" => $this->lowerBound,
            "upperBound" => min($this->upperBound, $this->totalCount),
            "link" => $this->link,
            "hasMorePages" => $this->hasMorePages,
            "hasAnyPagination" => ($this->lastPage !== 1),
        ];
    }

    private function buildSqlLimitClause(): int
    {
        if ($this->perPage === null) {
            $config = Configuration::getInstance();
            $this->perPage = $config->get("db.results.limit");
        }

        // Peek ahead to check for other pages
        return $this->perPage + 1;
    }

    private function buildSqlOffsetClause(): int
    {
        $this->lowerBound = intval( ($this->page - 1) * $this->perPage ) + 1;
        $this->upperBound = $this->lowerBound + $this->perPage - 1;
        
        return $this->lowerBound - 1;
    }

    private function buildPaginationLink(): string
    {
        return Uri::setQueryStringParameter(
            $this->link,
            $this->queryParam,
            $this->page
        );
    }
}
