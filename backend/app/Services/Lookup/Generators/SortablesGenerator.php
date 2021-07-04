<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class SortablesGenerator implements Generatable
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
