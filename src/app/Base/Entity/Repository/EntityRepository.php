<?php

namespace App\Base\Entity\Repository;

use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Base\Items\ItemsCollection;
use App\Base\Items\Interfaces\ItemsCollectionInterface;
use App\Base\Entity\Entity\EntityInterface;
use App\Base\Entity\Repository\EntityRepositoryInterface;

abstract class EntityRepository implements EntityRepositoryInterface
{
    /**
     * Override in child class
     * Ex.: 'cards'
     *
     * @var string
     */
    public $table = '';

    /**
     * Override in child class
     * Ex.: App\Entity\Card\Card::class
     *
     * @var string
     */
    public $entityClass = '';

    public function all(): ItemsCollectionInterface
    {
        $statement = (new SelectSqlStatement)
            ->from($this->table);

        $items = Database::getInstance()
            ->select($statement)
            ->get($this->entityClass);
    
        return (new ItemsCollection)->set($items);
    }

    public function findById($id): ?EntityInterface
    {   
        $statement = (new SelectSqlStatement)
            ->from($this->table)
            ->where("id = :id");

        return Database::getInstance()
            ->select($statement)
            ->bind([":id" => $id])
            ->first($this->entityClass);
    }

    public function findBy(string $field, $value): ?EntityInterface
    {
        $statement = (new SelectSqlStatement)
            ->from($this->table)
            ->where("{$field} = :value");

        return Database::getInstance()
            ->select($statement)
            ->bind([":value" => $value])
            ->first($this->entityClass);
    }

    public function findAllBy(string $field, $value): ItemsCollectionInterface
    {
        $statement = (new SelectSqlStatement)
            ->from($this->table)
            ->where("{$field} = :value");

        $items = Database::getInstance()
            ->select($statement)
            ->bind([":value" => $value])
            ->get($this->entityClass);

        return (new ItemsCollection)->set($items);
    }
}
