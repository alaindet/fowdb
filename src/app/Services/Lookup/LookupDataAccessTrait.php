<?php

namespace App\Services\Lookup;

use App\Services\Lookup\Exceptions\MissingDataException;

/**
 * Implements LookupDataAccessInterface on Lookup service
 * 
 * From App\Services\Lookup\Lookup
 * ===============================
 * protected $features; // @var array
 * protected $data; // @var array
 */
trait LookupDataAccessTrait
{
    /**
     * Reads data from the cached data
     * 
     * Ex.: $cache->get("rarities.id2code") => array
     *
     * @param string $path Dot-notation path
     * @return object|string|string[]
     */
    public function get(string $path)
    {
        // Directly return data (not-nested data)
        if (false === strpos($path, ".")) {

            // ERROR: Invalid path
            if (!isset($this->data->{$path})) {
                throw new MissingDataException($path);
            }

            return $this->data->{$path};
        }

        $result = $this->data;

        foreach (explode(".", $path) as $pathStep) {
            if (!isset($this->data->{$pathStep})) {
                throw new MissingDataException($path);
            }
            $result = $result->{$pathStep};
        }

        return $result;
    }

    /**
     * Returns all the cache data object
     *
     * @return object
     */
    public function getAll(): object
    {
        return $this->data;
    }

    public function exists(string $feature): bool
    {
        return in_array($feature, $this->features);
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
