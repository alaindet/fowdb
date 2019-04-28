<?php

namespace App\Base\Entity;

use App\Base\Entity\Entity;
use App\Base\Items\ItemsCollection;

interface EntityMapperInterface
{
    public function entityToDatabase(Entity $entity): array;
}
