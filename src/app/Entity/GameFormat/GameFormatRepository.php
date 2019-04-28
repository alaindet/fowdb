<?php

namespace App\Entity\GameFormat;

use App\Base\Entity\EntityRepository;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Base\Items\ItemsCollection;
use App\Base\Entity\GameFormat\GameFormat;
use App\Base\Entity\GameCluster\GameCluster;

class GameFormatRepository extends EntityRepository
{
    public $table = "game_formats";

    public function getClusters(GameFormat $format): ItemsCollection
    {
        $statement = (new SelectSqlStatement)
            ->select([
                "c.id",
                "c.code",
                "c.name",
            ])
            ->from("
                {$this->table} as f
                INNER JOIN pivot_cluster_format as cf ON cf.formats_id = f.id
                INNER JOIN game_clusters as c ON cf.clusters_id = c.id
            ")
            ->where("f.id = :id");

        $items = Database::getInstance()
            ->select($statement)
            ->bind([":id" => $format->id])
            ->get(GameCluster::class);

        return (new ItemsCollection)->set($items);
    }
}
