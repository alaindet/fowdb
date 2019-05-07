<?php

namespace App\Entity\GameFormat;

use App\Base\ORM\Entity\Entity;
use App\Base\ORM\Mapper\EntityMapper;
use App\Entity\GameFormat\GameFormat;

class GameFormatMapper extends EntityMapper
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
