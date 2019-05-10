<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Services\Database\StatementManager\StatementManager;
use App\Entity\GameSet\GameSet;

class SpoilersGenerator implements LookupDataGeneratorInterface
{
    public function generate(): object
    {
        $result = (object) [
            "sets"   => [],
            "ids"    => [],
            "names"  => [],
            "codes"  => [],
            "counts" => [],
        ];

        $replacement = StatementManager::new("select")
            ->orderBy("id DESC");

        $repository = EntityManager::getRepository(GameSet::class);
        $items = $repository
            ->setReplaceStatement($replacement)
            ->findAllBy("is_spoiler", 1);

        foreach ($items as $item) {
            $result->sets[] = $item;
            $result->ids[] = $item->id;
            $result->names[] = $item->name;
            $result->codes[] = $item->code;
            $result->counts[] = $item->count;
        }

        return $result;
    }
}
