<?php

namespace App\Base\Search;

use App\Services\Database\Statement\SelectSqlStatement;
use App\Base\Search\SearchInterface;

abstract class Search implements SearchInterface
{
    /**
     * The SQL statement object
     *
     * @var SelectSqlStatement
     */
    protected $statement;

    /**
     * Bindings for prepared SQL statement
     *
     * @var array
     */
    protected $bind = [];

    /**
     * Search filters, usually GET parameters
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Data fetched from the database
     *
     * @var array
     */
    protected $results = [];

    /**
     * Shared state across processors, if needed
     *
     * @var array
     */
    protected $state = [];

    /**
     * Override this in child class
     * Associative array mapping filter labels to its processor function
     *
     * @var array
     */
    protected $processors = [];

    /**
     * All pagination information
     *
     * @var array
     */
    private $pagination = [];

    /**
     * Pagination link
     *
     * @var string
     */
    private $paginationLink = '';

    /**
     * Initialize a new SQL statement object and set defaults
     */
    public function __construct()
    {
        $this->statement = new SelectSqlStatement;
        $this->setDefaults();
    }

    /**
     * Returns the current SQL statement
     *
     * @return string
     */
    public function getStatement(): string
    {
        return $this->statement->toString();
    }

    /**
     * Override this on child class to set default parameters
     *
     * @return Search
     */
    protected function setDefaults(): SearchInterface
    {
        return $this;
    }

    /**
     * Sets the search filters, later to be processed
     *
     * @param array $filters
     * @return SearchInterface
     */
    public function setFilters(array $filters = null): SearchInterface
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * Sets the pagination link to be used on page links
     *
     * @param string $link
     * @return SearchInterface
     */
    public function setPagination(string $link): SearchInterface
    {
        $this->paginationLink = $link;

        return $this;
    }

    /**
     * Return search filters as they were passed
     *
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Calls all input processing functions for each filter
     *
     * @return SearchInterface
     */
    public function processFilters(): SearchInterface
    {
        // Prepares some needed state variables
        $this->beforeProcessing();

        // Call processor functions of each filter
        // A processor function alters the SqlStatement object directly
        foreach ($this->filters as $key => $value) {

            // This filters out unvalid search filters
            if (isset($this->processors[$key])) {
                $processor = $this->processors[$key];
                $this->$processor($value, $this->state);
            }

        }

        // After processing: useful to call multi-input processors
        $this->afterProcessing();

        return $this;
    }

    /**
     * Fetches data and pagination info from database
     *
     * @return SearchInterface
     */
    public function fetchResults(): SearchInterface
    {
        // Instantiate the database
        $database = database()
            ->select($this->statement)
            ->bind($this->bind)
            ->page($this->filters['page'] ?? 1)
            ->paginationLink($this->paginationLink);

        // Fetch paginated data
        $this->results = $database->paginate();

        // Fetch pagination info
        $this->pagination = $database->paginationInfo();

        return $this;
    }

    /**
     * Override this on child class
     *
     * @return Search
     */
    protected function beforeProcessing(): SearchInterface
    {
        return $this;
    }

    /**
     * Override this on child class
     *
     * @return Search
     */
    protected function afterProcessing(): SearchInterface
    {
        return $this;
    }

    /**
     * Returns fetched pagination data from the database
     *
     * @return array
     */
    public function getPagination(): array
    {
        return $this->pagination;
    }

    /**
     * Returns fetched data from the database
     *
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }
}
