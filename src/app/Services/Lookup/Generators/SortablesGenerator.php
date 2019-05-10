<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Utils\Arrays;

class SortablesGenerator implements LookupDataGeneratorInterface
{
    public function generate(): object
    {
        return Arrays::toObject([
            "cards" => [
                "sets_id" => "Set",
                "num" => "Number",
                "race" => "Race/Trait",
                "attribute" => "Attribute",
                "total_cost" => "Total Cost",
                "rarity" => "Rarity",
                "atk" => "ATK",
                "def" => "DEF"
            ]
        ]);
    }
}
