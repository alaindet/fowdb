<?php

namespace App\Entity\GameSet;

use App\Base\ORM\Entity\Entity;

class GameSet extends Entity
{
    public $id;
    public $clusters_id;
    public $code;
    public $name;
    public $count;
    public $date_release;
    public $is_spoiler;
}
