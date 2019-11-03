<?php

namespace App\Services\Database;

use App\Utils\Uri;

/**
 * Can only be used by its base class:
 * App\Services\Database\Database
 */
trait Pageable
{
    private $page;
    private $perPage;
    private $totalCount;
    private $lastPage;
    private $hasMorePages;
    private $lowerBound;
    private $upperBound;
    private $link;
 
    /**
     * Sets the current page position
     *
     * @param integer $page
     * @return Database
     */
    public function page(int $page): Database
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Sets the number of results per page to return
     *
     * @param integer $perPage
     * @return Database
     */
    public function perPage(int $perPage): Database
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * Sets LIMIT clause value automatically
     *
     * @return void
     */
    private function setLimit(): void
    {
        // Use default
        if (!isset($this->perPage)) {
            $this->perPage = config('db.results.limit');
        }

        $this->statement->limit($this->perPage);
    }

    /**
     * Sets OFFSET clause value automatically
     *
     * @return void
     */
    private function setOffset(): void
    {
        if (!isset($this->page)) $this->page = 1;

        $this->lowerBound = intval(($this->page - 1) * $this->perPage);
        $this->upperBound = intval($this->lowerBound + $this->perPage);

        $this->statement->offset($this->lowerBound);
    }

    /**
     * Stores the pagination link to be used
     *
     * @param string $url
     * @return Database
     */
    public function paginationLink(string $url): Database
    {
        $this->link = Uri::removeQueryStringParameter($url, 'page');

        return $this;
    }

    /**
     * Paginates data and stores pagination basic info
     *
     * @param string $className
     * @param string $countableField
     * @return array
     */
    public function paginate(
        string $className = null,
        string $countableField = null
    ): array
    {
        $this->setLimit();

        $this->setOffset();

        // "Peek" ahead to see if there are more results after these
        $this->statement->limit(+1, $add = true);

        $this->totalCount = $this->count($countableField);

        $this->lastPage = intval(ceil($this->totalCount / $this->perPage));

        $results = $this->get($className);

        $this->hasMorePages = count($results) > $this->perPage;

        // Remove the last item (it's been used to peek)
        if ($this->hasMorePages) array_pop($results);

        return $results;
    }

    /**
     * Returns pagination info
     * 
     * MUST be called after paginate() to populate 'total' and 'last-page'
     *
     * @return array
     */
    public function paginationInfo(): array
    {
        return [
            'total' => $this->totalCount,
            'current-page' => $this->page,
            'last-page' => $this->lastPage,
            'more' => $this->hasMorePages,
            'lower-bound' => $this->lowerBound + 1,
            'upper-bound' => min($this->upperBound, $this->totalCount),
            'link' => $this->link,
            'has-pagination' => $this->lastPage !== 1,
            'per-page' => $this->perPage
        ];
    }
}
