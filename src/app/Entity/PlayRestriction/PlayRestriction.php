<?php

namespace App\Entity\PlayRestriction;

use App\Base\ORM\Entity\Entity;
use App\Entity\Card\Card;
use App\Entity\GameFormat\GameFormat;

class PlayRestriction extends Entity
{
    protected $table = "play_restrictions";
    protected $relationships = [
        Card::class => "n-1",
        GameFormat::class => "n-1",
    ];

    public $id;
    public $cards_id;
    public $formats_id;
    public $deck;
    public $copies;
}
