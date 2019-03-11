<?php

namespace App\Services\Lookup;

use App\Services\Lookup\Exceptions\MissingDataException;

/**
 * Implements LookupDataAccessInterface on Lookup service
 * 
 * From App\Services\Lookup\Lookup
 * ===============================
 * protected $features; // @var array
 */
trait LookupDataAccessTrait
{
    /**
     * All lookup data is stored here
     *
     * @var array
     */
    protected $data = [];

    /**
     * Reads data from the cached data
     * 
     * Ex.: $cache->get('rarities.id2code.1') => 'Common'
     *
     * @param string $path Dot-notation path
     * @return mixed string | string[]
     */
    public function get(string $path = null)
    {
        // ERROR: Missing name
        if (!isset($path)) {
            return $this->getAll();
        }

        // Directly return data (not-nested data)
        if (false === strpos($path, '.')) {

            // ERROR: Invalid path
            if (!isset($this->data[$path])) {
                throw new MissingDataException($path);
            }

            return $this->data[$path];

        } 

        // Split by the dot
        $pathBits = explode('.', $path);

        // Pop the first path bit, then dive 1 level deeper
        $firstPathBit = array_shift($bits);

        // ERROR: Invalid path
        if (!in_array($firstPathBit, $this->features)) {
            throw new MissingDataException($firstPathBit);
        }

        $result = $this->data[$firstPathBit];

        // Loop on all bits and dive deeper if needed
        foreach ($pathBits as $pathBit) {

            // ERROR: Invalid path
            if (!isset($result[$pathBit])) {
                $invalidPath = $firstPathBit.'.'.implode('.', $pathBits);
                throw new MissingDataException($invalidPath);
            }

            // Update the result and dive deeper
            $result = $result[$pathBit];
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
        return $this->data;
    }

    public function exists(string $feature): bool
    {
        return isset($this->data[$feature]);
    }

    /**
     * Returns the features names only
     *
     * @return array
     */
    public function features(): array
    {
        return $this->features;
    }
}
