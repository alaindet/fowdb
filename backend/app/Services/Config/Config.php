<?php

namespace App\Services\Config;

use App\Base\Singleton;
use App\Services\FileSystem\FileSystem;

class Config
{
    use Singleton;

    /**
     * Holds config data
     *
     * @var array
     */
    private $data = [];

    /**
     * Path of root of application
     *
     * @var string
     */
    private $srcPath;

    /**
     * Path to cached config file
     * Relative to root of application
     *
     * @var string
     */
    private $path = '/data/cache/config.php';

    /**
     * List of config builder classes (order matters)
     *
     * @var array
     */
    private $builders = [
        \App\Services\Config\Builders\PathsBuilder::class,
        \App\Services\Config\Builders\TimestampsBuilder::class,
        \App\Services\Config\Builders\EnvBuilder::class,
    ];

    /**
     * Private constructor for singleton implementation
     * 
     * Loads the config data (from cached file or builds it)
     */
    private function __construct(string $srcPath)
    {
        $this->srcPath = $srcPath;
        $this->path = $this->srcPath . $this->path;
        $this->load();
    }

    /**
     * Attempts to load config data from a cached file,
     * builds it otherwise
     *
     * @return void
     */
    private function load()
    {
        if (file_exists($this->path)) {
            $this->data = FileSystem::loadFile($this->path);
        } else {
            $this->build()->store();
        }
    }

    /**
     * Builds all config data via builder classes and populates self::data
     *
     * @return Config
     */
    public function build(): Config
    {
        foreach ($this->builders as $builderClass) {
            $builder = new $builderClass($this->srcPath);

            // Overrides old values but preserves any runtime value
            $this->data = array_merge($this->data, $builder->build());

        }

        return $this;
    }
    
    /**
     * Stores current config data into a cached config file
     *
     * @return Config
     */
    public function store(): Config
    {
        $contentLines = [];
        foreach ($this->data as $key => $value) {
            $contentLines[] = "'{$key}' => '{$value}'";
        }
        $content = "<?php return [". implode(",", $contentLines) ."];\n";
        FileSystem::saveFile($this->path, $content);

        return $this;
    }

    /**
     * Read some value by key
     *
     * @param string $key
     * @return mixed string|null
     */
    public function get(string $key)
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Stores a runtime value, gone with the request
     *
     * @param string $key
     * @param string $value
     * @return Config
     */
    public function set(string $key, string $value): Config
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Returns multiple config values
     * It's like calling self::get multiple times but avoids the overhead
     *
     * @param array $keys
     * @return array Array of strings
     */
    public function getByKeys(array $keys): array
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->data[$key] ?? null;
        }

        return $result;
    }

    /**
     * Returns absolute path of the cached configuration file
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }
}
