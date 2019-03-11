<?php

namespace App\Services\Lookup\Interfaces;

interface LookupDataAccessInterface
{
    public function get(string $path = null);
    public function getAll(): array;
    public function exists(string $feature): bool;
    public function features(): array;
}
