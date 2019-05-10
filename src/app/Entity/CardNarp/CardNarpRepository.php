<?php

namespace App\Entity\CardNarp;

use App\Base\ORM\Repository\Repository;
use App\Entity\CardNarp\CardNarp;

class CardNarpRepository extends Repository
{
    public $entityClass = CardNarp::class;
    public $table = "card_narps";
}
