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
use App\Services\Database\StatementManager\StatementManager;

/**
 * From App\Base\ORM\Repository\Repository
 * 
 * public $entityClass;
 * public $table;
 * public $foreignKey;
 * public $relationships;
 */
trait RepositoryReadTrait
{
    private $paginator = null;

    public function setPaginator(PaginatorInterface $paginator): self
    {
        $this->paginator = $paginator;
        return $this;
    }

    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    public function isPaginator(): bool
    {
        return $this->paginator !== null;
    }

    /**
     * Returns all entities in the base table
     * 
     * It combines the base statement with an extra statement if it was set
     * 
     * @return ItemsCollectionInterface
     */
    public function all(): ItemsCollectionInterface
    {
        // Define base statement
        $statement = (new SelectSqlStatement)
            ->from($this->table);

        // Combine it with an extra statement, if defined
        if ($this->isExtraStatement()) {
            $statement = $this->combineWithExtraStatement($statement);
        }

        // Use a paginator if defined
        if ($this->isPaginator()) {
            return $this->paginator
                ->setStatement($statement)
                ->fetch($this->entityClass)
                ->getResuts();
        }

        // Do not use any paginator
        return Database::getInstance()
            ->select($statement)
            ->bind($statement->getBoundValues())
            ->get($this->entityClass);
    }

    /**
     * Finds a single entity by its ID
     *
     * @param string|int $id
     * @return EntityInterface|null
     */
    public function findById($id): ?EntityInterface
    {   
        $statement = (new SelectSqlStatement)
            ->from($this->table)
            ->where("id = :id")
            ->setBoundValues([":id" => $id]);

        return Database::getInstance()
            ->select($statement)
            ->bind($statement->getBoundValues())
            ->first($this->entityClass);
    }

    /**
     * Finds a single entity with a single equality condition
     * It combines the base statement with an extra statement if it was set
     * 
     * NOTE:
     * Even if the condition returns more than one result,
     * only the first is returned
     * 
     * Ex.: $cardsRepo->findBy("code", "ABC-123");
     *
     * @param string $field
     * @param any $value
     * @return EntityInterface|null
     */
    public function findBy(string $field, $value): ?EntityInterface
    {
        $statement = (new SelectSqlStatement)
            ->from($this->table)
            ->where("{$field} = :value")
            ->setBoundValues([":value" => $value]);

        // Combine it with an extra statement, if defined
        if ($this->isExtraStatement()) {
            $statement = $this->combineWithExtraStatement($statement);
        }

        return Database::getInstance()
            ->select($statement)
            ->bind($statement->getBoundValues())
            ->first($this->entityClass);
    }

    /**
     * Returns a collection of entities matching the provided equality condition
     * It combines the base statement with an extra statement if it was set
     *
     * @param string $field
     * @param any $value
     * @return ItemsCollectionInterface
     */
    public function findAllBy(string $field, $value): ItemsCollectionInterface
    {
        $statement = (new SelectSqlStatement)
            ->from($this->table)
            ->where("{$field} = :value")
            ->setBoundValues([":value" => $value]);

        // Combine it with an extra statement, if defined
        if ($this->isExtraStatement()) {
            $statement = $this->combineWithExtraStatement($statement);
        }

        // Use a paginator if defined
        if ($this->isPaginator()) {
            return $this->paginator
                ->setStatement($statement)
                ->fetch($this->entityClass)
                ->getResuts();
        }

        // Do not use any paginator
        return Database::getInstance()
            ->select($statement)
            ->bind($statement->getBoundValues())
            ->get($this->entityClass);
    }

    /**
     * Fetches related entities based on this entity's relationships
     * It combines the base statement with an extra statement if it was set
     *
     * @param EntityInterface $sourceEntity
     * @param string $targetEntityClass
     * @param array $targetEntityFields Subset of fields of target entity class
     * @return EntityInterface|ItemsCollection
     */
    public function getRelated(
        EntityInterface $sourceEntity,
        string $targetEntityClass,
        array $targetEntityFields = null
    )
    {
        $relationship = $this->relationships[$targetEntityClass] ?? null;

        // ERROR: Missing relationship
        if ($relationship === null) {
            throw new RelationshipNotFoundException(
                "Entity with class {$targetEntityClass} not found or ".
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
        [$statementMethod, $databaseMethod] = $methods[$type];

        // Meta data from target entity
        $targetEntityMetaData = EntityManager::getMetaData($targetEntityClass);

        // Build the SqlStatement object
        $statement = $this->{$statementMethod}(
            $sourceEntity,
            $targetEntityMetaData,
            $targetEntityFields,
            $joinTable
        );

        // Combine it with an extra statement, if defined
        if ($this->isExtraStatement()) {
            $statement = $this->combineWithExtraStatement($statement);
        }

        // Use a paginator if defined
        if ($this->isPaginator()) {
            return $this->paginator
                ->setStatement($statement)
                ->fetch($targetEntityMetaData->entityClass)
                ->getResuts();
        }

        return Database::getInstance()
            ->select($statement)
            ->bind($statement->getBoundValues())
            ->{$databaseMethod}($targetEntityMetaData->entityClass);
    }

    private function buildOneToManyStatement(
        EntityInterface $sourceEntity,
        EntityMetaDataInterface $targetEntityMetaData,
        array $targetEntityFields = null
    ): SelectSqlStatement
    {
        return StatementManager::new("select")
            ->select($targetEntityFields ?? $targetEntityMetaData->fields)
            ->from($targetEntityMetaData->table)
            ->where("{$this->foreignKey} = :id")
            ->setBoundValues([
                ":id" => $sourceEntity->id
            ]);
    }

    private function buildManyToOneStatement(
        EntityInterface $sourceEntity,
        EntityMetaDataInterface $targetEntityMetaData,
        array $targetEntityFields = null
    ): SelectSqlStatement
    {
        return StatementManager::new("select")
            ->select($targetEntityFields ?? $targetEntityMetaData->fields)
            ->from($targetEntityMetaData->table)
            ->where("id = :id")
            ->setBoundValues([
                ":id" => $sourceEntity->{$targetEntityMetaData->foreignKey}
            ]);
    }

    private function buildManyToManyStatement(
        EntityInterface $sourceEntity,
        EntityMetaDataInterface $targetEntityMetaData,
        array $targetEntityFields = null,
        string $joinTable
    ): SelectSqlStatement
    {
        $fields = $targetEntityFields ?? $targetEntityMetaData->fields;
        $targetEntityTableAlias = "t";
        $joinTableAlias = "j";

        foreach ($fields as &$field) {
            $field = "{$targetEntityTableAlias}.{$field}";
        }

        return StatementManager::new("select")
            ->select($fields)
            ->from($joinTable, $joinTableAlias)
            ->innerJoin(
                [$targetEntityMetaData->table, $targetEntityTableAlias],
                "id",
                $targetEntityMetaData->foreignKey
            )
            ->where("j.{$this->foreignKey} = :id")
            ->setBoundValues([":id" => $sourceEntity->id]);
    }
}
