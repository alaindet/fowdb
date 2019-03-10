<?php

namespace App\Services\Configuration\Interfaces;

interface ConfigurationFileInterface
{
    public function setFilePath(string $path): ConfigurationFileInterface;
    public function setSourceDirectoryPath(string $path): ConfigurationFileInterface;
    public function getData(): array;
    public function process(): ConfigurationFileInterface;
}
