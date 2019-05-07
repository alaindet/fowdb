<?php

namespace App\Entity\GameRule;

use App\Base\ORM\Entity\Entity;

class GameRule extends Entity
{
    protected $table = "game_rules";

    public $id;
    public $date_created;
    public $date_validity;
    public $version;
    public $doc_path;
}
