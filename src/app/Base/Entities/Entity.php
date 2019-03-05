<?php

namespace App\Base\Entities;

use App\Base\Items\Item;

use App\Base\Entities\Exceptions\MissingPropertyNameException;
use App\Base\Entities\Exceptions\InvalidPropertyNameException;

abstract class Entity extends Item
{
    /**
     * Maps custom properties to their accessor methods
     *
     * @var array
     */
    protected $propertyAccessors = [
        // Ex.: 'foo' => 'getFooProperty'
    ];

    protected $properties = [];

    /**
     * Flag to use or avoid using the cache when reading properties
     *
     * @var bool
     */
    private $useCache = true;

    public function useCache(bool $use = null)
    {
        // Set
        if (isset($use)) {
            $this->useCache = $use;
            return $this;
        }

        // Get
        return $this->useCache;
    }

    /**
     * Gets a property of the entity
     * 
     * Properties can be
     * - native: direct property (comes from db)
     * - computed: via property accessor
     * - cached: a computed property already calculated
     *
     * @param string $propertyName
     * @param bool $forceCompute Forces to reset the cache, if present
     * @param array ...$args Extra arguments passed to the getter
     * @return mixed The value of the property (usually string|string[])
     */
    public function get(
        string $propertyName = null,
        bool $forceCompute = false,
        array ...$args
    )
    {
        // ERROR: Missing property name
        if (!isset($propertyName)) {
            throw new MissingPropertyNameException();
        }

        // Native property (from database)
        if (isset($this->$propertyName)) {
            return $this->$propertyName;
        }

        // Cached computed property
        if (isset($this->properties[$propertyName]) && !$forceCompute) {
            return $this->properties[$propertyName];
        }

        // ERROR: There's no property accessor for this property
        if (!isset($this->propertyAccessors[$propertyName])) {
            throw new InvalidPropertyNameException();
        }

        // Get the property accessor name
        $accessor = $this->propertyAccessors[$propertyName];

        // Execute the property accessor method and cache the result
        $this->properties[$propertyName] =  $this->$accessor($args);

        // Return cached value
        return $this->properties[$propertyName];
    }

    /**
     * Alias of Entity::get()
     *
     * @param string $propertyName
     * @return mixed
     */
    public function __get(string $propertyName)
    {
        return $this->get($propertyName);
    }
}
