<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Models\CardType;

class TypesGenerator implements Generatable
{
    public function generate(): array
    {
        // Build all basic maps
        $result = array_reduce(

            // Data
            database()
                ->select(
                    statement('select')
                        ->fields(['bit', 'name'])
                        ->from('card_types')
                        ->orderBy([$this->sortByGroup(), 'bit ASC'])
                )
                ->get(),
        
            // Reducer
            function ($result, $item) {
                $result['bit2name'][$item['bit']] = $item['name'];
                $result['name2bit'][$item['name']] = $item['bit'];
                return $result;
            },
            
            // Initial state
            [
                'bit2name'  => [],
                'name2bit'  => [],
            ]
        );

        // Add display types at the end (it needs the bit2name map)
        $result['display'] = $this->buildDisplayTypes($result['bit2name']);

        return $result;
    }

    /**
     * Builds the display types array like TYPE_NAME => BIT_VALUE
     * Ex.:
     * [
     *     [Ruler] => 1
     *     [J-Ruler] => 2
     *     [Basic Ruler] => 513
     *     [Basic J-Ruler] => 514
     *     [Resonator] => 8
     *     [Chant] => 16
     *     [Addition] => 32
     *     [Regalia] => 64
     *     [Rune] => 128
     *     [Master Rune] => 33554560
     *     [Basic Magic Stone] => 516
     *     [Special Magic Stone] => 260
     * ]
     *
     * @param array $map BIT => NAME map
     * @return array
     */
    private function buildDisplayTypes(array $map): array
    {
        return array_reduce(
            $this->displayTypes(),
            function ($types, $bits) use (&$map) {

                // Buld the single type
                $type = array_reduce(
                    $bits,
                    function ($type, $bit) use (&$map) {
                        $type['name'][] = $map[$bit];
                        $type['mask'] |= (1 << $bit);
                        return $type;
                    },
                    [
                        'name' => [],
                        'mask' => 0
                    ]
                );

                $name = implode(' ', $type['name']);
                $mask = $type['mask'];

                // Add
                $types[$name] = $mask;
                return $types;
            },
            []
        );

        return [];
    }

    /**
     * This array defines the rules to build the "display" card types, which are
     * what you see on the UI as a combination of types and pseudo-types as
     * defined on the 'card_types' table
     * 
     * Each element is an array listing the bit positions for each display type
     * Order is important
     *
     * @return array
     */
    private function displayTypes(): array
    {
        return [
            [0], // Ruler
            [1], // J-Ruler,
            [9, 0], // Basic Ruler
            [9, 1], // Basic J-Ruler
            [3], // Resonator
            [26], // Resonator (Stranger)
            [4], // Chant
            [5], // Addition
            [6], // Regalia
            [7], // Rune
            [25, 7], // Master Rune
            [1], // Magic Stone
            [9, 2], // Basic Magic Stone
            [8, 2], // Special Magic Stone
            
            [10], //Light Magic Stone
            [11], //Fire Magic Stone
            [12], //Water Magic Stone
            [13], //Wind Magic Stone
            [14], //Darkness Magic Stone

            [15], // Spell: Chant
            [16], // Spell: Chant-Instant
            [17], // Spell: Chant-Standby
            [18], // Addition: Field
            [19], // Addition: Resonator
            [20], // Addition: Ruler/J-Ruler
            [21], // Addition: J/Resonator
            [22], // True Magic Stone
        ];
    }

    /**
     * Generates a custom ORDER BY clause to sort types by their group
     *
     * @return string
     */
    private function sortByGroup(): string
    {
        $groups = [
            'general type',
            'current',
            'flag',
            'magic stone type',
            'legacy',
        ];

        $values = implode("','", $groups);

        return "FIELD(`group`, '{$values}')";
    }
}
