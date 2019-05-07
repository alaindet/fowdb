<?php

namespace App\Base\ORM\Entity;

abstract class EntityCustomProperties
{
    /**
     * Store previously calculated custom property
     * 
     * @var array
     */
    protected $properties = [];

    /**
     * Override in concrete class
     * Ex.: name => getter_function_name
     *
     * @var array
     */
    protected $getters = [];

    /**
     * Override in concrete class
     * Ex.: name => setter_function_name
     *
     * @var array
     */
    protected $setters = [];

    /**
     * Flag that could be used by getters (use lookup data, for example)
     *
     * @var bool
     */
    protected $useCache = true;

    /**
     * Back-reference to the entity
     *
     * @var App\Entity\Entity
     */
    protected $entity;

    public function __construct(Entity $reference)
    {
        $this->entity = $reference;
    }

    public function useCache(bool $useCache): self
    {
        $this->useCache = $useCache;
        return $this;
    }

    /**
     * Get a custom property value by name
     *
     * @param string $name
     * @param bool $force Force to recalculate the custom property
     * @return any
     */
    public function get(string $name, bool $force = false)
    {
        if (!$force && isset($this->properties[$name])) {
            return $this->properties[$name];
        }

        $getter = $this->getters[$name];
        $value = $this->$getter($this->useCache);
        $this->properties[$name] = $this->$getter($this->useCache);
        return $value;
    }

    /**
     * Set a custom property value by name
     *
     * @param string $name
     * @param any $value
     * @return self
     */
    public function set(string $name, $value): self
    {
        $setter = $this->setters[$name];
        $this->properties[$name] = $this->$setter($value);
        return $this;
    }
}
