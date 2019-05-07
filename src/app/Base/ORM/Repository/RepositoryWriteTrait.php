<?php

namespace App\Base\ORM\Repository;

use App\Base\ORM\Entity\Entity;
use App\Base\ORM\Manager\EntityManager;
use App\Services\Database\Database;
use App\Services\Database\Statement\InsertSqlStatement;

/**
 * Base class:
 * App\Base\ORM\Repository\EntityRepository
 * 
 * From base class:
 * public $entityClass;
 * public $table;
 * public $foreignKey;
 * public $relationships;
 */
trait RepositoryWriteTrait
{
    public function storeEntity(Entity $entity): void
    {
        $mapper = EntityManager::getMapper($this->entityClass);
        $row = $mapper->entityToDatabase($entity);

        $placeholders = [];
        $bind = [];
        foreach ($row as $key => $value) {
            $placeholder = ":{$key}";
            $placeholders[$key] = $placeholder;
            $bind[$placeholder] = $value;
        }

        $statement = (new InsertSqlStatement)
            ->table($this->table)
            ->values($placeholders);

        (Database::getInstance())
            ->insert($statement)
            ->bind($bind)
            ->execute();
    }
}
