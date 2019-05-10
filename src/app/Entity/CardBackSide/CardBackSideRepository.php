<?php

namespace App\Entity\CardBackSide;

use App\Base\ORM\Repository\Repository;
use App\Entity\CardBackSide\CardBackSide;

class CardBackSideRepository extends Repository
{
    public $entityClass = CardBackSide::class;
    public $table = "card_back_sides";
}
