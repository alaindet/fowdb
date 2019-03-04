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
    protected $properties = [
        // Ex.: 'foo' => 'getFooProperty'
    ];

    public function get(string $propertyName = null, array ...$args)
    {
        // ERROR: Missing property name
        if (!isset($propertyName)) {
            throw new MissingPropertyNameException();
        }

        // Return public property from database
        if (isset($this->$propertyName)) {
            return $this->$propertyName;
        }

        // ERROR: There's no custom property with this name
        if (!isset($this->properties[$propertyName])) {
            throw new InvalidPropertyNameException();
        }

        // Get the property accessor name
        $accessor = $this->properties[$propertyName];

        // Execute the property accessor method
        return $this->$accessor($args);
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
