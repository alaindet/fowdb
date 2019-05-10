<?php

namespace App\Entity\GameCluster;

use App\Base\ORM\Entity\Entity;
use App\Base\ORM\Mapper\EntityMapper;

class GameClusterMapper extends EntityMapper
{
    public function entityToDatabase(Entity $entity): array
    {
        return [
            "id" => $entity->id,
            "code" => $entity->code,
            "name" => $entity->name,
        ];
    }
}
