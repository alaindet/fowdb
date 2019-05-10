<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Entity\CardNarp\CardNarp;

class NarpsGenerator implements LookupDataGeneratorInterface
{
    public function generate(): object
    {
        $result = (object) [
            "id2code" => new \stdClass(),
            "id2name" => new \stdClass(),
        ];

        $repository = EntityManager::getRepository(CardNarp::class);

        foreach ($repository->all() as $item) {

            // Watch out: id !== value (check the db)
            $id = $item->value;
            $idLabel = "id" . $item->value;
            $name = $item->name;
            $code = $item->code;

            $result->id2code->{$idLabel} = $code;
            $result->id2name->{$idLabel} = $name;

        }

        return $result;
    }
}
