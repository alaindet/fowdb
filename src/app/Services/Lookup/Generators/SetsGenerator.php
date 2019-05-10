<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Entity\GameSet\GameSet;

class SetsGenerator implements LookupDataGeneratorInterface
{
    public function generate(): object
    {
        $result = (object) [
            "code2id"   => new \stdClass(),
            "code2name" => new \stdClass(),
            "id2code"   => new \stdClass(),
            "id2name"   => new \stdClass(),
        ];

        $repository = EntityManager::getRepository(GameSet::class);

        foreach ($repository->all() as $item) {

            $id = $item->id;
            $idLabel = "id" . $item->id;
            $name = $item->name;
            $code = $item->code;

            $result->code2id->{$code} = $id;
            $result->code2name->{$code} = $name;
            $result->id2code->{$idLabel} = $code;
            $result->id2name->{$idLabel} = $name;

        }

        return $result;
    }
}
