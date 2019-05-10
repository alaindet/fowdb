<?php

namespace App\Entity\CardType;

use App\Base\ORM\Repository\Repository;
use App\Entity\CardType\CardType;

class CardTypeRepository extends Repository
{
    public $entityClass = CardType::class;
    public $table = "card_types";
}
