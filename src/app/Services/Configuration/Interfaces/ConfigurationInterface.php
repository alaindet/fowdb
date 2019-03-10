<?php

namespace App\Services\Configuration\Interfaces;

interface ConfigurationInterface
{
    public function get(string $key): ?string;
    public function getAll(): array;
    public function set(string $key, string $value): ConfigurationInterface;
    public function load(): ConfigurationInterface;
    public function store(): ConfigurationInterface;
    public function generate(): array;
    public function build(): ConfigurationInterface;
    public function clear(): ConfigurationInterface;
}
