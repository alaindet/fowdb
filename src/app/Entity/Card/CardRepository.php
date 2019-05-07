<?php

namespace App\Entity\Card;

use App\Base\ORM\Repository\Repository;
use App\Entity\Card\Card;
use App\Entity\PlayRestriction\PlayRestriction;
use App\Entity\GameRuling\GameRuling;
use App\Entity\GameCluster\GameCluster;
use App\Entity\GameSet\GameSet;

class CardRepository extends Repository
{
    public $entityClass = Card::class;
    public $table = "cards";
    public $foreignKey = "cards_id";
    public $relationships = [
        PlayRestriction::class => "1-n",
        GameRuling::class => "1-n",
        GameCluster::class => "n-1",
        GameSet::class => "n-1",
    ];
}
