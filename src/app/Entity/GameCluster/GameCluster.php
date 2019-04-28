<?php

namespace App\Entity\GameCluster;

use App\Base\Entity\Entity;

class GameCluster extends Entity
{
    static public $table = "game_clusters";

    public $id;
    public $code;
    public $name;
}
