<?php

namespace App\Entity\GameCluster;

use App\Base\Entity\Entity\Entity;
use App\Entity\GameCluster\GameClusterCustomProperties;

class GameCluster extends Entity
{
    public $id;
    public $code;
    public $name;

    protected $customProperties = GameClusterCustomProperties::class;
}
