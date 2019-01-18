<?php

namespace App\Base\Search;

interface SearchInterface
{
    public function setFilters(array $filters = null): SearchInterface;
    public function getFilters(): array;
    public function setPagination(string $link): SearchInterface;
    public function getPagination(): array;
    public function processFilters(): SearchInterface;
    public function fetchResults(): SearchInterface;
    public function getResults(): array;
}
