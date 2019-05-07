<?php

namespace App\Base\ORM\Entity;

use App\Base\ORM\Entity\EntityCustomProperties;
use App\Base\ORM\Interfaces\EntityInterface;

/**
 * Native properties are those fetched from the database, they are public
 * Custom properties are display-only properties and are on a separate class,
 * only loaded if needed
 * 
 * In concrete child class, override these
 * 
 * public $dbTable;
 * public $dbForeignKey;
 */
abstract class Entity implements EntityInterface
{
    protected $customProperties;
    protected $customPropertiesInstance;

    public function props(): ?EntityCustomProperties
    {
        // Return existing instance
        if ($this->customPropertiesInstance !== null) {
            return $this->customPropertiesInstance;
        }

        // First time calling a custom property, instantiate the class
        if ($this->customProperties !== null) {
            $props = new $this->customProperties($this);
            $this->customPropertiesInstance = $props;
            return $props;
        }

        // This entity has no custom properties
        return null;
    }
}
