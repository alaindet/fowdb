<?php

namespace App\Entity\GameCluster;

use App\Base\ORM\Entity\Entity;
use App\Base\ORM\Mapper\EntityMapper;
use App\Entity\GameCluster\GameCluster;

class GameClusterMapper extends EntityMapper
{
    public function getGameFormats(): ManyToManyRelationship
    {
        $toTable = get_class_vars(GameFormatRepository::class);

        $manyToMany = (new ManyToManyRelationship)
            ->setFromTable($this->table, "gc")
            ->setToTable($toTable["table"], "gf")
            ->setToFields($gameFormatFields ?? $toTable["tableFields"])
            ->setJoinTable("join__game_clusters__game_formats", "cf")
            ->setJoinFields(["clusters_id", "id"], ["formats_id", "id"]);
    }

    public function entityToDatabase(Entity $entity): array
    {
        return [
            "id" => $entity->id,
            "code" => $entity->code,
            "name" => $entity->name,
        ];
    }
}
