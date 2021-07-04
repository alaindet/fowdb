<?php

namespace App\Services\Lookup;

use App\Base\Singleton;
use App\Exceptions\FileSystemException;
use App\Exceptions\LookupException;
use App\Services\FileSystem\FileSystem;
use App\Services\Lookup\Generators\AttributesGenerator;
use App\Services\Lookup\Generators\LayoutsGenerator;
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
    private $data = [];

    /**
     * Path of the cache file
     *
     * @var string
     */
    private $path = "/data/cache/lookup.txt";

    /**
     * List of generator classes by feature
     *
     * @var array
     */
    private $generators = [
        "attributes" => AttributesGenerator::class,
        "layouts"    => LayoutsGenerator::class,
        "clusters"   => ClustersGenerator::class,
        "costs"      => CostsGenerator::class,
        "divinities" => DivinitiesGenerator::class,
        "formats"    => FormatsGenerator::class,
        "narps"      => NarpsGenerator::class,
        "rarities"   => RaritiesGenerator::class,
        "sets"       => SetsGenerator::class,
        "sortables"  => SortablesGenerator::class,
        "spoilers"   => SpoilersGenerator::class,
        "types"      => TypesGenerator::class,
    ];

    /**
     * Holds all the features' names (filled from self::generators on runtime)
     *
     * @var array
     */
    private $features;

    /**
     * Defined the cache file location and loads it
     */
    private function __construct()
    {
        $this->path = path_src($this->path);
        $this->features = array_keys($this->generators);
        $this->load();
    }

    /**
     * Loads the cached lookup file
     *
     * @return Lookup
     */
    private function load(): Lookup
    {
        try {
            $this->data = unserialize(FileSystem::readFile($this->path));
        } catch (FileSystemException $exception) {
            $this->build()->store();
        } finally {
            return $this;
        }
    }

    /**
     * Stores current data into cached file, usually called after self::build()
     *
     * @return Lookup
     */
    public function store(): Lookup
    {
        FileSystem::saveFile($this->path, serialize($this->data));

        return $this;
    }

    /**
     * If $feature is null, build all data!
     * If $feauure is a string, build data for that single feature
     * If $feature is an array, build data for those features
     *
     * @param string|array|null $feature
     * @return Lookup
     */
    public function build($feature = null): Lookup
    {
        $features = [];

        if ($feature === null) {
            $features = $this->features;
        } else {
            $features = (is_array($feature)) ? $feature : [$feature];
        }

        foreach ($features as $feature) {
            $generatorClass = $this->generators[$feature];
            $this->data[$feature] = (new $generatorClass)->generate();
        }

        return $this;
    }

    /**
     * Reads just an element of the cached array
     * Can return array or string based on the $path
     * 
     * Ex.: $lookup->get("rarities.id2code.1"); // Common
     *
     * @param string $path Dot-notation path
     * @return mixed string | string[]
     */
    public function get(string $path = null)
    {
        // ERROR: Missing name
        if (!isset($path)) {
            throw new LookupException("Not path provided");
        }

        // Directly return data (not-nested data)
        if (false === strpos($path, ".")) {
            return $this->data[$path];
        }

        // Split by the dot
        $bits = explode(".", $path);

        // Pop the first bit, then dive 1 level deeper
        $first = array_shift($bits);

        // ERROR: Invalid path
        if (!in_array($first, $this->features)) {
            throw new LookupException("Feature \"{$first}\" doesn't exist");
        }

        $result = $this->data[$first];

        // Loop on all bits and dive deeper if needed
        foreach ($bits as &$bit) {
            if (isset($result[$bit])) $result = $result[$bit];
        }

        return $result;
    }

    /**
     * Returns all the cached array
     *
     * @return array
     */
    public function getAll(): array
    {
        return $this->data;
    }

    /**
     * Returns the features names only
     *
     * @return array
     */
    public function getFeatures(): array
    {
        return $this->features;
    }
}
