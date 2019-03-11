<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;

class SortablesGenerator implements LookupDataGeneratorInterface
{
    public function generate(): array
    {
        return [
            'cards' => [
                'sets_id' => 'Set',
                'num' => 'Number',
                'race' => 'Race/Trait',
                'attribute' => 'Attribute',
                'total_cost' => 'Total Cost',
                'rarity' => 'Rarity',
                'atk' => 'ATK',
                'def' => 'DEF'
            ]
        ];
    }
}
