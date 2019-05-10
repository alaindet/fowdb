<?php

namespace App\Entity\CardAttribute;

use App\Base\ORM\Repository\Repository;
use App\Entity\CardAttribute\CardAttribute;

class CardAttributeRepository extends Repository
{
    public $entityClass = CardAttribute::class;
    public $table = "card_attributes";
}
