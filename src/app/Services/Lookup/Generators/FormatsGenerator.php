<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Entity\GameFormat\GameFormat;
use App\Entity\GameCluster\GameCluster;
use function GuzzleHttp\Psr7\readline;

class FormatsGenerator implements LookupDataGeneratorInterface
{
    public function generate(): object
    {
        $result = (object) [
            "id2code"       => new \stdClass(),
            "id2name"       => new \stdClass(),
            "id2clusters"   => new \stdClass(),
            "code2id"       => new \stdClass(),
            "code2name"     => new \stdClass(),
            "default"       => "",
            "display"       => new \stdClass(),
        ];

        $repository = EntityManager::getRepository(GameFormat::class);
        $items = $repository->all();

        foreach ($items as $item) {

            $idLabel = "id" . $item->id;

            $clusters = $repository
                ->getRelated($item, GameCluster::class)
                ->extract(["id", "code", "name"])
                ->toArray();

            if ($item->is_default) {
                $result->default = $item->code;
            }

            if ($item->is_multi_cluster) {
                $result->display->{$item->code} = $item->name;
            }

            $result->code2id->{$item->code} = $item->id;
            $result->code2name->{$item->code} = $item->name;
            $result->id2code->{$idLabel} = $item->code;
            $result->id2name->{$idLabel} = $item->name;
            $result->id2clusters->{$idLabel} = $clusters;

        }
        
        return $result;
    }
}
