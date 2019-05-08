<?php

namespace App\Services\Configuration;

use App\Base\Singleton;
use App\Services\FileSystem\FileSystem;
use App\Services\FileSystem\Exceptions\FileNotFoundException;
use App\Services\Configuration\Interfaces\ConfigurationInterface;
use App\Services\Configuration\Files\Environment;
use App\Services\Configuration\Files\Timestamps;
use App\Services\Configuration\Files\Directories;
use App\Services\FileSystem\FileFormat\PhpArray;

/**
 * This class completely manages the application configuration data
 * It cannot safely rely on any service using config data
 */
class Configuration implements ConfigurationInterface
{
    use Singleton;

    /**
     * Configuration data as key => value (1-dimensional array)
     * 
     * Keys must use the dot notation (Ex.: "app.name")
     * Values must be strings
     *
     * @var array
     */
    private $data;

    /**
     * List of all config file classes, they set config data when called by
     * $this->generate()
     * 
     * Order matters:
     * If two file classes set the same config key, the latter is used
     *
     * @var array
     */
    private $files = [
        Environment::class,
        Timestamps::class,
        Directories::class,
    ];

    /**
     * Holds all file paths and directory paths
     *
     * @var array
     */
    private $paths = [];

    /**
     * Private constructor for singleton implementation
     * 
     * Loads the data (from cache file or from normal file)
     */
    private function __construct()
    {
        $src = dirname(dirname(dirname(__DIR__)));

        // Define paths
        $this->paths = [
            'dir.src' => $src,
            'file.cache' => $src . '/data/cache/config.php',
        ];

        $this->load();
    }

    /**
     * Loads all relevant configuration data from cache file
     * Builds a new cache file if needed
     *
     * @return void
     */
    public function load(): ConfigurationInterface
    {
        try {
            $this->data = FileSystem::loadFile($this->paths['file.cache']);
        } catch (FileNotFoundException $exception) {
            $this->build();
        }

        return $this;
    }

    /**
     * Gathers all configuration data and stores it into $this->data
     *
     * @return array
     */
    public function generate(): array
    {
        $data = [];
        foreach ($this->files as $configFileClass) {
            $configFile = new $configFileClass;
            $configFile->setSourceDirectoryPath($this->paths['dir.src']);
            $configFile->process();
            $fileData = $configFile->getData();
            $data = array_merge($data, $fileData);
        }
        return $data;
    }

    /**
     * Stores config data on the filesystem
     *
     * @return void
     */
    public function store(): ConfigurationInterface
    {
        $configCachedFile = new PhpArray($this->paths['file.cache']);
        $configCachedFile->setData($this->data);
        $configCachedFile->setMinify(true);
        $configCachedFile->writeDataToFile();
        $configCachedFile->writeFile();

        return $this;
    }

    /**
     * Reads configuration data, returns NULL if missing
     *
     * @param string $key Uses dot notation (Ex.: "app.name")
     * @return string|null
     */
    public function get(string $key): ?string
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Returns all current configuration data
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->data;
    }

    /**
     * Sets configuration data. Exists only in runtime if you don't call store()
     *
     * @param string $key Must use dot notation
     * @param string $value
     * @return ConfigurationInterface
     */
    public function set(string $key, $value): ConfigurationInterface
    {
        $this->data[$key] = $value;
        return $this;
    }

    public function build(): ConfigurationInterface
    {
        $this->data = $this->generate();
        $this->store();
        return $this;
    }

    public function clear(): ConfigurationInterface
    {
        FileSystem::deleteFile($this->paths['file.cache']);
        return $this;
    }
}
