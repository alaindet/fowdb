<?php

namespace App\Entities\Game\Set;

use App\Entities\Game\Set\Set;
use App\Entities\Game\Set\Sets;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;

class SetsRepository
{
    private $db = null;
    private $setsTable = 'game_sets';
    private $setsEntity = Set::class;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(array $fields = []): Sets
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->setsTable);

        $items = $this->db
            ->select($statement)
            ->get($this->setsEntity);

        $collection = (new Sets)
            ->set($items);

        return $collection;
    }

    public function findAllByClusterId(
        int $clusterId,
        array $fields = []
    ): Sets
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->setsTable)
            ->where('clusters_id = :clusterid');

        $items = $this->db
            ->select($statement)
            ->bind([':clusterid' => $clusterId])
            ->get($this->setsEntity);

        $collection = (new Sets)
            ->set($items);

        return $collection;
    }
}
