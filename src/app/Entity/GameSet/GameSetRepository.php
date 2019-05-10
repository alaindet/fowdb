<?php

namespace App\Entity\GameSet;

use App\Base\ORM\Repository\Repository;
use App\Entityt\Card\Card;
use App\Entity\GameSet\GameSet;
use App\Entity\GameCluster\GameCluster;

class GameSetRepository extends Repository
{
    public $entityClass = GameSet::class;
    public $table = "game_sets";
    public $foreignKey = "sets_id";
    public $relationships = [
        Card::class => "1-n",
        GameCluster::class => "n-1",
    ];
}
