<?php

namespace App\Base\Entity\Entity;

use App\Base\Entity\Entity\EntityCustomProperties;
use App\Base\Entity\Entity\EntityInterface;

/**
 * Native properties are those fetched from the database
 * Custom properties are relationships, display props etc.
 * 
 * Custom properties live in a separate class which is loaded on demand
 */
abstract class Entity implements EntityInterface
{
    protected $customProperties;
    protected $customPropertiesInstance;

    public function props(): EntityCustomProperties
    {
        if ($this->customPropertiesInstance === null) {
            $this->customPropertiesInstance = new $this->customProperties($this);
        }

        return $this->customPropertiesInstance;
    }
}
