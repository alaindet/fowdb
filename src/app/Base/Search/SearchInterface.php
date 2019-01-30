<?php

namespace App\Base\Search;

interface SearchInterface
{
    public function setParameters(array $filters = null): SearchInterface;
    public function getParameters(): array;
    public function processParameters(): SearchInterface;
    public function setPagination(string $link): SearchInterface;
    public function getPagination(): array;
    public function fetchResults(): SearchInterface;
    public function getResults(): array;
}
