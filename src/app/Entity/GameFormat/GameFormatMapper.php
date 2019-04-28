<?php

namespace App\Entity\GameFormat;

use App\Base\Entity\Entity;
use App\Base\Entity\GameFormat\GameFormat;
use App\Base\Entity\EntityMapperInterface;

class GameFormatMapper implements EntityMapperInterface
{
    public function entityToDatabase(Entity $entity): array
    {
        return [
            'id' => $entity->id,
            'name' => $entity->name,
            'code' => $entity->code,
            'desc' => $entity->desc ?? NULL,
            'is_default' => $entity->is_default ?? 0,
            'is_multi_cluster' => $entity->is_multi_cluster ?? 0,
        ];
    }
}
