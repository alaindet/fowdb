<?php

namespace App\Services\Lookup\Interfaces;

interface LookupDataAccessInterface
{
    public function get(string $path); // object|string[]|string|int
    public function getAll(): object;
    public function getFeatures(): array;
    public function exists(string $feature): bool;
}
