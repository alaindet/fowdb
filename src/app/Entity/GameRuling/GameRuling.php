<?php

namespace App\Entity\GameRuling;

use App\Base\ORM\Entity\Entity;
use App\Entity\Card\Card;

class GameRuling extends Entity
{
    // protected $table = "game_rulings";
    // protected $relationships = [
    //     Card::class => "n-1",
    // ];

    public $id;
    public $cards_id;
    public $date;
    public $is_errata;
    public $text;
}
