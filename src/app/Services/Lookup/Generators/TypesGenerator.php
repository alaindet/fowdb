<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Models\CardType;

class TypesGenerator implements Generatable
{
    public function generate(): array
    {
        $items = (new CardType)->all();

        $result = [
            'bit2code'  => [],
            'bit2name'  => [],
            'code2bit'  => [],
            'code2name' => [],
            'display'   => $this->legacyTypes()
        ];

        return array_reduce($items, function ($result, $item) {

            $result['bit2code'][$item['bit']] = $item['code'];
            $result['bit2name'][$item['bit']] = $item['name'];
            $result['code2bit'][$item['code']] = $item['bit'];
            $result['code2name'][$item['code']] = $item['name'];

            return $result;

        }, $result);
    }

    private function legacyTypes(): array
    {
        return [
            'Ruler',
            'J-Ruler',
            'Resonator',
            'Chant',
            'Chant/Rune',
            'Master Rune',
            'Addition',
            'Regalia',
            'Rune',
            'Magic Stone',
            'Special Magic Stone',
            'Special Magic Stone/True Magic Stone',
            'Spell:Chant',
            'Spell:Chant-Instant',
            'Spell:Chant-Standby',
            'Addition:Field',
            'Addition:J/Resonator',
            'Addition:Resonator',
            'Addition:Ruler/J-Ruler',
        ];
    }
}
