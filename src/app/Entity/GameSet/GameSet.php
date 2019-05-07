<?php

namespace App\Entity\GameSet;

use App\Base\ORM\Entity\Entity;
use App\Entity\Card\Card;
use App\Entity\GameCluster\GameCluster;

class GameSet extends Entity
{
    protected $table = "game_sets";
    protected $foreignKey = "sets_id";
    protected $relationships = [
        Card::class => "1-n",
        GameCluster::class => "n-1",
    ];

    public $id;
    public $clusters_id;
    public $code;
    public $name;
    public $count;
    public $date_release;
    public $is_spoiler;
}
