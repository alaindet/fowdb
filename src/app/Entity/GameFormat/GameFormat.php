<?php

namespace App\Entity\GameFormat;

use App\Base\Entity\Entity;
use App\Base\Entity\GameFormat\GameFormatCustomProperties;

class GameFormat extends Entity
{
    public $id;
    public $name;
    public $code;
    public $desc;
    public $is_default;
    public $is_multi_cluster;

    protected $customProperties = GameFormatCustomProperties::class;
}
