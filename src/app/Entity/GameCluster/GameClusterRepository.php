<?php

namespace App\Entity\GameCluster;

use App\Base\ORM\Repository\Repository;
use App\Entity\GameCluster\GameCluster;
use App\Entity\GameFormat\GameFormat;
use App\Entity\GameSet\GameSet;

class GameClusterRepository extends Repository
{
    public $entityClass = GameCluster::class;
    public $table = "game_clusters";
    public $foreignKey = "clusters_id";
    public $relationships = [
        GameFormat::class => ["n-n", "join__game_clusters__game_formats"],
        GameSet::class => "1-n",
    ];
}
