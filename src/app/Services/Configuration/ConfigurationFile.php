<?php

namespace App\Services\Configuration;

use App\Base\Items\Item;
use App\Services\Configuration\Interfaces\ConfigurationFileInterface;
use App\Services\FileSystem\FileSystem;

class ConfigurationFile extends Item implements ConfigurationFileInterface
{
    /**
     * The path to the cached configuration file to load
     * Must be relative to /src/, it's converted to full path on runtime
     *
     * @var string
     */
    protected $filePath;

    /**
     * Array of config data that will later be
     * merged with *ALL* other config data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Full path to /src/
     *
     * @var string
     */
    protected $srcDirPath;

    public function setSourceDirectoryPath(string $path): ConfigurationFileInterface
    {
        $this->srcDirPath = $path;
        return $this;
    }

    public function setFilePath(string $path): ConfigurationFileInterface
    {
        $this->filePath = $path;
        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Builds the absolute file path of the configuration file
     * It *MUST* be called after setSourceDirectoryPath()
     *
     * @return string
     */
    protected function buildAbsoluteFilePath(string $path): string
    {
        return "{$this->srcDirPath}/{$path}";
    }

    /**
     * Overridden by child class
     *
     * @return void
     */
    public function process(): ConfigurationFileInterface
    {
        // All logic here
        
        return $this;
    }
}
