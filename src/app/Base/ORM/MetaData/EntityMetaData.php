<?php

namespace App\Base\ORM\MetaData;

use App\Base\ORM\Interfaces\EntityMetaDataInterface;

class EntityMetaData implements EntityMetaDataInterface
{
    /**
     * Fully-qualified class name of the entity
     *
     * @var string;
     */
    public $entityClass;

    /**
     * Table name for this entity
     *
     * @var string
     */
    public $table;

    /**
     * List of fields
     *
     * @var array
     */
    public $fields;

    /**
     * Optional: foreign key of this table used on other tables
     *
     * @var string
     */
    public $foreignKey;

    /**
     * Optional: List of relationships with other entities
     *
     * @var array
     */
    public $relationships;
}
