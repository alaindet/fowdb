<?php

namespace App\Base\ORM\Interfaces;

use App\Base\ORM\Entity\Entity;
use App\Base\Items\ItemsCollection;

interface EntityMapperInterface
{
    public function entityToDatabase(Entity $entity): array;
}
