<?php

namespace App\Entity\CardRarity;

use App\Base\ORM\Repository\Repository;
use App\Entity\CardRarity\CardRarity;

class CardRarityRepository extends Repository
{
    public $entityClass = CardRarity::class;
    public $table = "card_rarities";
}
