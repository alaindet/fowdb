<?php

namespace App\Services\Lookup;

use App\Utils\Strings;
use App\Utils\Json;
use App\Services\Lookup\Interfaces\LookupDataBuildInterface;
use App\Services\FileSystem\FileSystem;
use App\Services\FileSystem\Exceptions\FileNotFoundException;

/**
 * From App\Services\Lookup\Lookup
 * ===============================
 * protected $features; // @var array
 * protected $data; // @var array
 */
trait LookupDataBuildTrait
{
    /**
     * Location of the cache file
     *
     * @var string
     */
    private $cacheFilePath;

    public function setCacheFilePath(string $path): LookupDataBuildInterface
    {
        $this->cacheFilePath = $path;        
        return $this;
    }

    public function load(): LookupDataBuildInterface
    {
        try {
            $this->data = FileSystem::loadJsonFile($this->cacheFilePath);
        } catch (FileNotFoundException $exception) {
            $this->build();
        }

        return $this;
    }

    /**
     * Builds a new cache file, overwrites the old one if needed
     *
     * @return LookupDataBuildInterface
     */
    public function build(): LookupDataBuildInterface
    {
        $this->data = new \stdClass();
        $this->generateAll();
        $this->store();

        return $this;
    }

    /**
     * Calls all feature generator classes, see Lookup::generate for more
     * 
     * @return LookupDataBuildInterface
     */
    public function generateAll(): LookupDataBuildInterface
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
     * @return LookupDataBuildInterface
     */
    public function generate(string $feature): LookupDataBuildInterface
    {
        $generator = $this->getGeneratorClass($feature);
        $this->data->{$feature} = (new $generator)->generate();
        
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
    private function getGeneratorClass(string $feature): string
    {
        $featureClass = Strings::kebabToPascal($feature);
        return "\\App\\Services\\Lookup\\Generators\\{$featureClass}Generator";
    }

    /**
     * Stores all current lookup data in a cache file
     *
     * @return LookupDataBuildInterface
     */
    public function store(): LookupDataBuildInterface
    {
        $fileContent = Json::fromObject($this->data);
        FileSystem::saveFile($this->cacheFilePath, $fileContent);

        return $this;
    }
}
