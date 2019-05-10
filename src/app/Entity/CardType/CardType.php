<?php

namespace App\Entity\CardType;

use App\Base\ORM\Entity\Entity;

class CardType extends Entity
{
    public $id;
    public $bit;
    public $code;
    public $group;
    public $name;
}
