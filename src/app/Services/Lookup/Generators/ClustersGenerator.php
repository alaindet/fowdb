<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Entity\GameCluster\GameCluster;
use App\Entity\GameFormat\GameFormat;
use App\Entity\GameSet\GameSet;

class ClustersGenerator implements LookupDataGeneratorInterface
{
    public function generate(): object
    {
        $result = (object) [
            "list"       => new \stdClass(),
            "code2name"  => new \stdClass(),
            "code2id"    => new \stdClass(),
            "id2code"    => new \stdClass(),
            "id2name"    => new \stdClass(),
            "id2formats" => new \stdClass(),
        ];

        $repository = EntityManager::getRepository(GameCluster::class);
        $items = $repository->all();

        foreach ($items as $item) {

            $idLabel = "id" . $item->id;

            $formats = $repository
                ->getRelated($item, GameFormat::class)
                ->extract(["id", "code", "name"])
                ->toArray();

            $sets = $repository
                ->getRelated($item, GameSet::class)
                ->extract(["id", "code", "name"])
                ->toArray();

            $result->id2code->{$idLabel} = $item->code;
            $result->id2name->{$idLabel} = $item->name;
            $result->id2formats->{$idLabel} = $formats;
            $result->code2id->{$item->code} = $item->id;
            $result->code2name->{$item->code} = $item->name;

            $listItem = new \stdClass();
            $listItem->name = $item->name;
            $listItem->sets = $sets;
            $result->list->{$item->code} = $listItem;

        }

        return $result;
    }
}
