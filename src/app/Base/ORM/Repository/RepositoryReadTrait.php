<?php

namespace App\Base\ORM\Repository;

use App\Base\ORM\Interfaces\EntityInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Base\Items\Interfaces\ItemsCollectionInterface;
use App\Base\Items\ItemsCollection;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Services\Database\Interfaces\PaginatorInterface;
use App\Base\ORM\Exceptions\RelationshipNotFoundException;
use App\Base\ORM\Interfaces\EntityMetaDataInterface;

/**
 * Base class:
 * App\Base\ORM\Repository\Repository
 * 
 * From base class:
 * public $entityClass;
 * public $table;
 * public $foreignKey;
 * public $relationships;
 */
trait RepositoryReadTrait
{
    private $paginator;

    public function setPaginator(PaginatorInterface $paginator): self
    {
        $this->paginator = $paginator;
        return $this;
    }

    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    public function all(): ItemsCollectionInterface
    {
        $statement = (new SelectSqlStatement)
            ->from($this->table);

        if ($this->paginator !== null) {
            return $this->paginator
                ->setStatement($statement)
                ->fetch($this->entityClass)
                ->getResuts();
        }

        return Database::getInstance()
            ->select($statement)
            ->get($this->entityClass);
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

        if ($this->paginator !== null) {
            return $this->paginator
                ->setStatement($statement)
                ->fetch($this->entityClass)
                ->getResuts();
        }

        return Database::getInstance()
            ->select($statement)
            ->bind([":value" => $value])
            ->get($this->entityClass);
    }

    /**
     * Fetches related entities based on this entity's relationships
     *
     * @param EntityInterface $sourceInstance
     * @param string $targetClass
     * @param array $targetFields Subset of fields of target entity class
     * @return EntityInterface|ItemsCollection
     */
    public function getRelated(
        EntityInterface $sourceInstance,
        string $targetClass,
        array $targetFields = null
    )
    {
        $relationship = $this->relationships[$targetClass] ?? null;

        // ERROR: Missing relationship
        if ($relationship === null) {
            throw new RelationshipNotFoundException(
                "Entity with class {$targetClass} not found or ".
                "not related to {$this->entityClass}"
            );
        }

        // Set relationship type and optionally the join table (n-n)
        (is_array($relationship))
            ? [$type, $joinTable] = $relationship
            : [$type, $joinTable] = [$relationship, null];

        // relationship => [statement_builder, database_fetching_method]
        $methods = [
            "1-n" => ["buildOneToManyStatement", "get"],
            "n-1" => ["buildManyToOneStatement", "first"],
            "n-n" => ["buildManyToManyStatement", "get"],
        ];

        $targetMetaData = EntityManager::getMetaData($targetClass);

        [$statementMethod, $databaseMethod] = $methods[$type];
        $statement = $this->{$statementMethod}(
            $sourceInstance,
            $targetMetaData,
            $targetFields,
            $joinTable
        );

        if ($this->paginator !== null) {
            return $this->paginator
                ->setStatement($statement)
                ->fetch($targetMetaData->entityClass)
                ->getResults();
        }

        return Database::getInstance()
            ->select($statement)
            ->bind($statement->getBoundValues())
            ->{$databaseMethod}($targetMetaData->entityClass);
    }

    private function buildOneToManyStatement(
        EntityInterface $sourceInstance,
        EntityMetaDataInterface $targetMetaData,
        array $targetFields = null
    ): SelectSqlStatement
    {
        return (new SelectSqlStatement)
            ->select($targetFields ?? $targetMetaData->fields)
            ->from($targetMetaData->table)
            ->where("{$this->foreignKey} = :id")
            ->setBoundValues([
                ":id" => $sourceInstance->id
            ]);
    }

    private function buildManyToOneStatement(
        EntityInterface $sourceInstance,
        EntityMetaDataInterface $targetMetaData,
        array $targetFields = null
    ): SelectSqlStatement
    {
        return (new SelectSqlStatement)
            ->select($targetFields ?? $targetMetaData->fields)
            ->from($targetMetaData->table)
            ->where("id = :id")
            ->setBoundValues([
                ":id" => $sourceInstance->{$targetMetaData->foreignKey}
            ]);
    }

    private function buildManyToManyStatement(
        EntityInterface $sourceInstance,
        EntityMetaDataInterface $targetMetaData,
        array $targetFields = null,
        string $joinTable
    ): SelectSqlStatement
    {
        $fields = $targetFields ?? $targetMetaData->fields;
        $targetTableAlias = "t";
        $joinTableAlias = "j";

        foreach ($fields as &$field) {
            $field = "{$targetTableAlias}.{$field}";
        }

        return (new SelectSqlStatement)
            ->select($fields)
            ->from($joinTable, $joinTableAlias)
            ->innerJoin(
                [$targetMetaData->table, $targetTableAlias],
                "id",
                $targetMetaData->foreignKey
            )
            ->where("j.{$this->foreignKey} = :id")
            ->setBoundValues([":id" => $sourceInstance->id]);
    }
}
