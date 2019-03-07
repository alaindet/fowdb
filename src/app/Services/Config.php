<?php

namespace App\Services;

use App\Base\Singleton;
use App\Services\FileSystem\FileSystem;
use App\Exceptions\ConfigNotFoundException;

class Config
{
    use Singleton;

    /**
     * Configuration data
     *
     * @var array
     */
    private $data;

    /**
     * src/ directory path
     *
     * @var string
     */
    private $srcPath;

    /**
     * Cache file path
     *
     * @var string
     */
    private $cachePath;

    /**
     * Timestamps file path
     *
     * @var string
     */
    private $normalPath;

    /**
     * Private constructor for singleton implementation
     * 
     * Loads the data (from cache file or from normal file)
     */
    private function __construct()
    {
        // Define paths
        $this->srcPath = dirname(dirname(__DIR__));
        $this->cachePath = $this->srcPath . '/data/cache/config/env.php';
        $this->normalPath = $this->srcPath . '/.env';
        $this->timestampsPath = $this->srcPath . '/data/app/timestamps.php';

        $this->load();
    }

    /**
     * Read data from the configuration
     *
     * @param string $name Name of the configuration variable
     * @return string Value of the configuration variable
     */
    public function get(string $name): string
    {
        // ERROR: Missing config data
        if (!isset($this->data[$name])) {
            throw new ConfigNotFoundException(
                "Config \"{$name}\" not found"
            );
        }

        return $this->data[$name];
    }

    /**
     * Sets new data to given key ($name) or overwrites existing data on $name
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    /**
     * Attempts to load data from the cache,
     * Loads from .env file and parses it otherwise
     *
     * @return void
     */
    private function load()
    {
        if (file_exists($this->cachePath)) {
            $this->data = FileSystem::loadFile($this->cachePath);
        } else {
            $this->cache();
        }
    }

    /**
     * Adds runtime variables to the configuration data, like directories
     *
     * @param bool $dotNotation Turns the keys to dot-notation
     * @return array
     */
    private function runtimeVariables(bool $dotNotation = true): array
    {
        $runtime = [

            // Directories
            'DIR_ROOT' => dirname($this->srcPath),
            'DIR_SRC' => $this->srcPath,
            'DIR_APP' => $this->srcPath.'/app',
            'DIR_VIEWS' => $this->srcPath.'/resources/views',
            'DIR_DATA' => $this->srcPath.'/data',
            'DIR_CACHE' => $this->srcPath.'/data/cache',

        ];

        // Convert keys to dot-notation
        if ($dotNotation) {
            $result = [];
            foreach ($runtime as $key => $value) {
                $key = strtolower(str_replace('_','.',$key));
                $result[$key] = $value;
            }
            $runtime = $result;
        }

        return $runtime;
    }

    /**
     * Adds timestamps variables to the configuration data,
     * used to bust the cache on views
     *
     * @param bool $dotNotation Turns the keys to dot-notation
     * @return array
     */
    private function timestampsVariables(bool $dotNotation = true): array
    {
        $variables = FileSystem::loadFile($this->timestampsPath);

        // Convert keys to dot-notation
        if ($dotNotation) {
            $result = [];
            foreach ($variables as $key => $value) {
                $key = strtolower(str_replace('_','.',$key));
                $result[$key] = $value;
            }
            $variables = $result;
        }

        return $variables;
    }

    /**
     * Reads all config data and caches it on the filesystem on a single file
     *
     * @return void
     */
    public function cache()
    {
        // Load .env file
        $envFileContent = FileSystem::readFile($this->normalPath);

        $this->data = array_merge(
            $this->envToArray($envFileContent),
            $this->timestampsVariables(),
            $this->runtimeVariables()
        );
        
        // Build the .php file with the data array
        $temp = [];
        foreach ($this->data as $key => $val) {
            $temp[] = "'{$key}'=>'{$val}'";
        }
        $temp = implode(',', $temp);
        $content = "<?php return [{$temp}];\n";

        // Save it on the disk
        FileSystem::saveFile($this->cachePath, $content);
    }

    /**
     * .env file to array conversion
     * 
     * Skips empty lines, comment lines and intra-line comments
     * Parses values within quotes correctly
     * Returns an associative array
     * Optionally turns variable names into lowercase dot notation
     * Ex.: APP_NAME => app.name
     *
     * @param $envFileContent
     * @param $dotNotation (Optional) APP_NAME => app.name
     * @return array
     */
    public static function envToArray(
        string $envFileContent,
        bool $dotNotation = true
    ): array
    {
        $data = [];

        $lines = explode("\n", $envFileContent);

        foreach ($lines as $line) {

            // Skip empty lines and comments
            if ($line === '' || $line[0] === '#') continue;

            // Read the line length
            $length = strlen($line);

            // Check for intra-line comments
            if (false !== $hash = strpos($line, '#')) {

                // Strip any whitespace left to the comment
                while ($line[$hash] === ' ' || $line[$hash] === '#') $hash--;

                // Update line length
                $length = $hash;
            }

            // Find position of first equals symbol
            $equals = strpos($line, '=');

            // Pull everything to the left of the first equals
            $key = substr($line, 0, $equals);

            // Pull everything to the right from the equals to end of the line
            $value = substr($line, ($equals + 1), $length - $equals);

            // Erase quotes if present in $value
            $value = str_replace('"', '', $value);

            // Dot notation?
            if ($dotNotation) $key = strtolower(str_replace('_','.',$key));

            // Store variable
            $data[$key] = $value;
        }

        return $data;
    }
}
