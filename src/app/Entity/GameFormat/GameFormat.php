<?php

namespace App\Entity\GameFormat;

use App\Base\ORM\Entity\Entity;

class GameFormat extends Entity
{
    public $id;
    public $name;
    public $code;
    public $desc;
    public $is_default;
    public $is_multi_cluster;
}
