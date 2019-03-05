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
    protected $parameters = [];

    /**
     * Override this in child class
     * Assoc array mapping current filter names to their processors
     *
     * @var array
     */
    protected $parameterProcessors = [];

    /**
     * Override this in child class
     * Assoc array mapping filter aliases (ex.: legacy) to current filters
     *
     * @var array
     */
    protected $parameterAliases = [];

    /**
     * Array of filters which are always accepted
     *
     * @var array
     */
    private $parameterAlways = [
        "page",
    ];

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
     * Sets the search filters, later to be processed
     *
     * @param array $parameters
     * @return SearchInterface
     */
    public function setParameters(array $parameters = null): SearchInterface
    {
        foreach ($parameters as $name => $value) {

            // Skip invalid values
            if (
                (is_string($value) && $value === '') ||
                (is_array($value) && $value[0] === '')
            ) continue;

            // Always allowed filters
            if (in_array($name, $this->parameterAlways)) {
                $this->parameters[$name] = $value;
                continue;
            }

            // Current filter
            if (isset($this->parameterProcessors[$name])) {
                $this->parameters[$name] = $value;
                continue;
            }

            // Alias filter
            if (isset($this->parameterAliases[$name])) {
                $name = $this->parameterAliases[$name];
                $this->parameters[$name] = $value;
                continue;
            }

        }

        return $this;
    }

    /**
     * Return search filters as they were passed
     *
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Calls all input processing functions for each filter
     * 
     * Validation and sanitization is already performed before calling this
     *
     * @return SearchInterface
     */
    public function processParameters(): SearchInterface
    {
        // Prepares some needed state variables
        $this->beforeProcessing();

        // Call processor functions of each filter
        // A processor function alters the SqlStatement object directly
        foreach ($this->parameters as $name => $value) {
            $processor = $this->parameterProcessors[$name] ?? false;
            if (!$processor) continue;
            $this->$processor($value, $this->state);
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
            ->page($this->parameters['page'] ?? 1)
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

    /**
     * Returns bound data to prepared statement
     *
     * @return array
     */
    public function getBoundData(): array
    {
        return $this->bind;
    }
}
