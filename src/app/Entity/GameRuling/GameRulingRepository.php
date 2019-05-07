<?php

namespace App\Entity\GameRuling;

use App\Base\ORM\Repository\Repository;
use App\Entity\GameRuling\GameRuling;

class GameRulingRepository extends Repository
{
    public $entityClass = GameRuling::class;
    public $table = "game_rulings";
}
