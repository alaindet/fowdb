<?php

namespace App\Entity\PlayRestriction;

use App\Base\ORM\Repository\Repository;
use App\Entity\PlayRestriction\PlayRestriction;

class PlayRestrictionRepository extends Repository
{
    public $entityClass = PlayRestriction::class;
    public $table = "play_restrictions";
}
