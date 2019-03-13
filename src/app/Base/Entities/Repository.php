<?php

namespace App\Base\Entities;

use App\Base\Entities\Interfaces\RepositoryInterface;
use App\Base\Items\ItemsCollection;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;

class Repository implements RepositoryInterface
{
    protected $db;
    protected $table; // 'game_formats'
    protected $sorting = [];
    protected $entityClass; // Ex.: App\Entities\Game\Format\Format::class
    protected $entitiesClass; // Ex.: App\Entities\Game\Format\Formats::class

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Returns all entities from the same table as a collection
     *
     * @param array $fields
     * @return ItemsCollection
     */
    public function all($fields = []): ItemsCollection
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->table)
            ->orderBy($this->sorting);

        $items = $this->db
            ->select($statement)
            ->get($this->entityClass);

        return (new $this->entitiesClass)
            ->set($items);
    }

    /**
     * Returns a single entity
     *
     * @param mixed $id int|string
     * @param array $fields
     * @return Card
     */
    public function findById($id, array $fields = []): Card
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->table)
            ->where('id = :id')
            ->limit(1);

        $item = $this->db
            ->select($statement)
            ->bind([':id' => $id])
            ->get($this->entityClass);

        return $item;
    }

    /**
     * Sets the sorting criteria
     *
     * @param array $sorting
     * @return Repository
     */
    public function sorting(array $sorting): Repository
    {
        $this->sorting = $sorting;
        return $this;
    }
}
