<?php

namespace App\Base\ORM\Repository;

use App\Base\ORM\Interfaces\RepositoryInterface;
use App\Base\ORM\Repository\RepositoryReadTrait;
use App\Base\ORM\Repository\RepositoryWriteTrait;
use App\Base\ORM\Repository\RepositoryExtraStatementTrait;

/**
 * In concrete child class, define these
 * 
 * public $entityClass;
 * public $table;
 * public $foreignKey; // Optional
 * public $relationships; // Optional
 */
abstract class Repository implements RepositoryInterface
{
    use RepositoryExtraStatementTrait;
    use RepositoryReadTrait;
    use RepositoryWriteTrait;

    /**
     * Fully-qualified class name of the entity
     *
     * @var string
     */
    public $entityClass;

    /**
     * The table name for this entity
     * 
     * @var string
     */
    public $table;

    /**
     * The table fields for this entity
     *
     * @var array
     */
    public $fields;

    /**
     * The name of foreign keys pointing to this table's ID field
     * The name must remain the same throughout the database
     * It can be null if no table references this table with a foreign key
     *
     * @var string|null
     */
    public $foreignKey;

    /**
     * List of relationships with other entities
     * 
     * Ex.: [
     *   FooEntity::class => "1-n",
     *   BarEntity::class => "n-1",
     *   BazEntity::class => ["n-n", "join_table_name"]
     * ]
     *
     * @var array
     */
    public $relationships;
}
