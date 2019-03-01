<?php

namespace App\Services\Lookup;

use App\Base\Singleton;
use App\Exceptions\FileSystemException;
use App\Exceptions\LookupException;
use App\Services\FileSystem;
use App\Services\Lookup\Generators\AttributesGenerator;
use App\Services\Lookup\Generators\BackSidesGenerator;
use App\Services\Lookup\Generators\BannedGenerator;
use App\Services\Lookup\Generators\ClustersGenerator;
use App\Services\Lookup\Generators\CostsGenerator;
use App\Services\Lookup\Generators\DivinitiesGenerator;
use App\Services\Lookup\Generators\FormatsGenerator;
use App\Services\Lookup\Generators\NarpsGenerator;
use App\Services\Lookup\Generators\RaritiesGenerator;
use App\Services\Lookup\Generators\SetsGenerator;
use App\Services\Lookup\Generators\SortablesGenerator;
use App\Services\Lookup\Generators\SpoilersGenerator;
use App\Services\Lookup\Generators\TypesGenerator;

/**
 * This library generates, reads and stores domain-specific data
 * Used throughout the application
 */
class Lookup
{
    use Singleton;

    /**
     * Holds all the cached data loaded from file, as an array
     *
     * @var array
     */
    private $cache = [];

    /**
     * Location of the cache file
     *
     * @var string
     */
    private $cacheFilename;

    /**
     * Will hold temporary generated content before saving the cache file
     *
     * @var array
     */
    private $generated = [];

    /**
     * List of generator classes for features. Order reflects importance
     *
     * @var array
     */
    public $features = [
        'attributes' => AttributesGenerator::class,
        'backsides'  => BackSidesGenerator::class,
        'banned'     => BannedGenerator::class,
        'clusters'   => ClustersGenerator::class,
        'costs'      => CostsGenerator::class,
        'divinities' => DivinitiesGenerator::class,
        'formats'    => FormatsGenerator::class,
        'narps'      => NarpsGenerator::class,
        'rarities'   => RaritiesGenerator::class,
        'sets'       => SetsGenerator::class,
        'sortables'  => SortablesGenerator::class,
        'spoilers'   => SpoilersGenerator::class,
        'types'      => TypesGenerator::class,
    ];

    /**
     * Defined the cache file location and loads it
     */
    private function __construct()
    {
        $this->cacheFilename = path_cache('lookup/lookup.txt');
        $this->load();
    }

    /**
     * Loads the cached lookup file
     *
     * @return void
     */
    private function load(): void
    {
        try {
            $this->cache = unserialize(
                FileSystem::readFile($this->cacheFilename)
            );
        }
        
        // ERROR: Missing cached file!
        catch (FileSystemException $exception) {
            $this->cache = [];
        }
    }

    /**
     * Stores all the current cache data in the cache file
     * Usually called after Lookup::generate() or Lookup::generateAll()
     *
     * @return Lookup
     */
    public function cache(): Lookup
    {
        // Update in-memory data before saving the file
        foreach ($this->generated as $name => $content) {
            $this->cache[$name] = $content;
        }

        // Save new cache file
        FileSystem::saveFile($this->cacheFilename, serialize($this->cache));

        return $this;
    }

    /**
     * Calls the specific feature generator by its name
     * The generator function generates new data,
     * then swaps the old data with the new one at runtime
     *
     * @param string $name Name of the feature to re-generate
     * @return Lookup
     */
    public function generate($feature): Lookup
    {
        $generatorClass = $this->features[$feature];
        $this->generated[$feature] = (new $generatorClass)->generate();
        
        return $this;
    }

    /**
     * Calls all the available generator classes
     *
     * @return $this
     */
    public function generateAll(): Lookup
    {
        foreach (array_keys($this->features) as $feature) {
            $this->generate($feature);
        }

        return $this;
    }

    /**
     * Reads just an element of the cache array
     * Can return array or string based on the $path
     * 
     * Ex.: $cache->get('rarities.id2code.1'); // Common
     *
     * @param string $path Dot-notation path
     * @return mixed string | string[]
     */
    public function get(string $path = null)
    {
        // ERROR: Missing name
        if (!isset($path)) return $this->getAll();

        // Directly return data (not-nested data)
        if (false === strpos($path, '.')) {

            // ERROR: Wrong path
            if (!isset($this->cache[$path])) {
                throw new LookupException("Feature \"{$path}\" doesn't exist");
            }

            return $this->cache[$path];

        } 

        // Split by the dot
        $bits = explode('.', $path);

        $features = array_keys($this->features);

        // Pop the first bit, then dive 1 level deeper
        $first = array_shift($bits);

        // ERROR: Invalid path
        if (!in_array($first, $features)) {
            throw new LookupException("Feature \"{$first}\" doesn't exist");
        }

        $result = $this->cache[$first];

        // Loop on all bits and dive deeper if needed
        foreach ($bits as &$bit) {
            if (isset($result[$bit])) $result = $result[$bit];
        }

        return $result;
    }

    /**
     * Returns all the cache array
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->cache;
    }

    public function exists(string $feature): bool
    {
        return isset($this->cache[$feature]);
    }

    /**
     * Returns the features names only
     *
     * @return array
     */
    public function features(): array
    {
        return array_keys($this->features);
    }
}
