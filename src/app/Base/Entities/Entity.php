<?php

namespace App\Base\Entities;

use App\Base\Items\Item;
use App\Base\Entities\Interfaces\EntityInterface;
use App\Base\Entities\Exceptions\MissingPropertyGetterException;
use App\Base\Entities\Exceptions\MissingPropertySetterException;

abstract class Entity extends Item implements EntityInterface
{
    /**
     * Maps custom properties to their getters
     *
     * @var array
     */
    protected $propertyGetters = [
        // Ex.: 'foo' => 'getFooProperty'
    ];

    /**
     * Maps custom properties to their setters
     *
     * @var array
     */
    protected $propertySetters = [
        // Ex.: 'foo' => 'setFooProperty'
    ];

    protected $properties = [];

    /**
     * Flag to use or avoid using the cache when reading properties
     *
     * @var bool
     */
    private $useCache = true;

    /**
     * Gets or sets the "useCache" flag
     *
     * @param bool $use
     * @return mixed Entity|bool
     */
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
     * Sets all database properties on creation, if needed
     *
     * @param array $data Ideally, a database row
     */
    public function __construct(array $data = null) {
        if (isset($data)) {
            foreach ($data as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Gets a property of the entity
     * 
     * Properties can be
     * - native: direct property (comes from db)
     * - computed: built by custom property getter
     * - cached: a computed property already built
     *
     * @param string $propertyName
     * @param bool $bypassCache Bypass cache and rebuild property
     * @param array ...$args Extra arguments passed to the getter
     * @return mixed The value of the property (usually string|string[])
     */
    public function get(
        string $propertyName,
        bool $bypassCache = false,
        array ...$args
    )
    {
        // Native property (from database)
        if (isset($this->$propertyName)) {
            return $this->$propertyName;
        }

        // Cached computed property
        if (isset($this->properties[$propertyName]) && !$bypassCache) {
            return $this->properties[$propertyName];
        }

        // ERROR: There's no property getter for this property
        if (!isset($this->propertyGetters[$propertyName])) {
            throw new MissingPropertyGetterException();
        }

        // Get the property accessor name
        $getter = $this->propertyGetters[$propertyName];

        // Execute the property accessor method and cache the result
        $this->properties[$propertyName] =  $this->$getter($args);

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

    /**
     * Sets a property of the entity
     * 
     * Properties can be
     * - native: direct property (comes from db)
     * - computed: built by custom property setter
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @param array ...$args Extra arguments passed to the setter
     * @return Entity
     */
    public function set(
        string $propertyName,
        $propertyValue,
        array ...$args
    ): Entity
    {
        // Native property (from database)
        if (isset($this->$propertyName)) {
            $this->$propertyName = $propertyValue;
            return $this;
        }

        // ERROR: There's no property accessor for this property
        if (!isset($this->propertySetters[$propertyName])) {
            throw new MissingPropertySetterException();
        }

        // Get the property accessor name
        $setter = $this->propertySetters[$propertyName];

        // Execute the property accessor method and cache the result
        $this->properties[$propertyName] = $this->$setter($propertyValue, $args);

        return $this;
    }

    /**
     * Alias of Entity::set()
     *
     * @param string $propertyName
     * @param mixed $propertyValue
     * @return mixed
     */
    public function __set(string $propertyName, $propertyValue)
    {
        return $this->set($propertyName, $propertyValue);
    }
}
