<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Entity\CardAttribute\CardAttribute;

class AttributesGenerator implements LookupDataGeneratorInterface
{
    public function generate(): object
    {
        $result = (object) [
            "bit2code"  => new \stdClass(),
            "bit2name"  => new \stdClass(),
            "code2bit"  => new \stdClass(),
            "code2name" => new \stdClass(),
            "name2bit"  => new \stdClass(),
            "name2code" => new \stdClass(),
            "display"   => new \stdClass(),
        ];

        $repository = EntityManager::getRepository(CardAttribute::class);

        foreach ($repository->all() as $item) {

            $name = $item->name;
            $code = $item->code;
            $bit = $item->bit;

            $result->bit2code->{$bit} = $code;
            $result->bit2name->{$bit} = $name;
            $result->code2bit->{$code} = $bit;
            $result->code2name->{$code} = $name;
            $result->name2bit->{$name} = $bit;
            $result->name2code->{$name} = $code;
            
            // code2name list of just attributes with display = 1
            if ($item->display) {
                $result->display->{$code} = $name;
            }

        }

        return $result;
    }
}
