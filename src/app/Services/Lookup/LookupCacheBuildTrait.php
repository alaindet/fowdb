<?php

namespace App\Services\Lookup;

use App\Utils\Strings;
use App\Services\Lookup\Interfaces\LookupInterface;
use App\Services\FileSystem\FileSystem;
use App\Services\FileSystem\Exceptions\FileNotFoundException;

/**
 * Implements LookupCacheBuildInterface on Lookup service
 * 
 * From App\Services\Lookup\Lookup
 * ===============================
 * protected $features; // @var array
 * 
 * From App\Services\Lookup\LookupDataAccessTrait
 * ==============================================
 * protected $data; // @var array
 */
trait LookupCacheBuildTrait
{
    /**
     * Location of the cache file
     *
     * @var string
     */
    private $cacheFilePath;

    public function setCacheFilePath(string $path): LookupInterface
    {
        $this->cacheFilePath = $path;
        return $this;
    }

    /**
     * Loads the cached lookup file
     *
     * @return void
     */
    public function load(): LookupInterface
    {
        try {
            $fileContent = FileSystem::loadFile($this->cacheFilePath);
            $this->data = unserialize($fileContent);
        } catch (FileNotFoundException $exception) {
            $this->build();
        }
        return $this;
    }

    /**
     * Builds a new cache file, overwrites the old one if needed
     *
     * @return LookupInterface
     */
    public function build(): LookupInterface
    {
        $this->generateAll();
        $this->store();
        return $this;
    }

    /**
     * Calls all feature generator classes, see Lookup::generate for more
     *
     * @return array
     */
    public function generateAll(): LookupInterface
    {
        foreach ($this->features as $feature) {
            $this->generate($feature);
        }

        return $this;        
    }

    /**
     * Calls a specific generator class which updates data about given feature
     *
     * @param string $feature Ex.: 'attributes', 'types', etc.
     * @return LookupInterface
     */
    public function generate(string $feature): LookupInterface
    {
        $generatorClass = $this->generatorClass($feature);
        $generator = new $generatorClass();
        $this->data[$feature] = $generator->generate();
        return $this;
    }

    /**
     * Builds the generator class name dinamically
     * 
     * Ex.:
     * feature => rarities
     * class   => \App\Services\Lookup\Generators\RaritiesGenerator
     *
     * @param string $feature
     * @return string
     */
    private function generatorClass(string $feature): string
    {
        return (
            "\\App\\Services\\Lookup\\Generators\\".
            Strings::kebabToPascal($feature).
            "Generator"
        );
    }

    /**
     * Stores all current lookup data in a cache file
     *
     * @return LookupInterface
     */
    public function store(): LookupInterface
    {
        $fileContent = serialize($this->data);
        FileSystem::saveFile($this->cacheFilePath, $fileContent);
        return $this;
    }
}
