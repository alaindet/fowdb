<?php

namespace App\Entity\GameFormat;

use App\Base\ORM\Repository\Repository;
use App\Entity\GameCluster\GameCluster;
use App\Entity\GameFormat\GameFormat;

class GameFormatRepository extends Repository
{
    public $entityClass = GameFormat::class;
    public $table = "game_formats";
    public $foreignKey = "formats_id";
    public $relationships = [
        GameCluster::class => ["n-n", "join__game_clusters__game_formats"],
    ];
}
