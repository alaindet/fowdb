<?php

namespace App\Entity\GameFormat;

use App\Base\Entity\Repository\EntityRepository;
use App\Base\Items\ItemsCollection;
use App\Entity\GameCluster\GameCluster;
use App\Entity\GameFormat\GameFormat;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;

class GameFormatRepository extends EntityRepository
{
    public $table = "game_formats";
    public $tableAlias = "f";

    public function getClusters(GameFormat $format): ItemsCollection
    {
        $statement = (new SelectSqlStatement)
            ->select([
                "c.id",
                "c.code",
                "c.name",
            ])
            ->from($this->table, $this->tableAlias)
            ->innerJoin(["pivot_cluster_format", "cf"], "formats_id", "id")
            ->innerJoin(["game_clusters", "c"], "id", "clusters_id")
            ->where("{$this->tableAlias}.id = :id");

        $items = Database::getInstance()
            ->select($statement)
            ->bind([":id" => $format->id])
            ->get(GameCluster::class);

        return (new ItemsCollection)->set($items);
    }
}
