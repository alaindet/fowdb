<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Entity\CardBackSide\CardBackSide;

class BackSidesGenerator implements LookupDataGeneratorInterface
{
    public function generate(): object
    {
        $result = (object) [
            "code2id"   => new \stdClass(),
            "code2name" => new \stdClass(),
            "id2code"   => new \stdClass(),
            "id2name"   => new \stdClass(),
        ];
        
        $repository = EntityManager::getRepository(CardBackSide::class);

        foreach ($repository->all() as $item) {

            $id = $item->id;
            $code = $item->code;
            $name = $item->name;

            $result->code2id->{$code} = $id;
            $result->code2name->{$code} = $name;
            $result->id2code->{$id} = $code;
            $result->id2name->{$id} = $name;
            
        }

        return $result;
    }
}
