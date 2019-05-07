<?php

namespace App\Entity\GameCluster;

use App\Base\ORM\Entity\Entity;
use App\Entity\Card\Card;
use App\Entity\GameSet\GameSet;

class GameCluster extends Entity
{
    public $id;
    public $code;
    public $name;
}
