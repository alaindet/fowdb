<?php

namespace App\Entities\Game\Cluster;

use App\Entities\Game\Cluster\Cluster;
use App\Entities\Game\Cluster\Clusters;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;

class ClustersRepository
{
    private $db = null;
    private $clustersTable = 'game_clusters';
    private $clustersEntity = Cluster::class;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function all(array $fields = []): Clusters
    {
        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->clustersTable);

        $items = $this->db
            ->select($statement)
            ->get($this->clustersEntity);

        $collection = (new Clusters)
            ->set($items);

        return $collection;
    }
}
