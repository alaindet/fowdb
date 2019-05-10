<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Base\ORM\Manager\EntityManager;
use App\Entity\CardType\CardType;
use App\Base\Items\ItemsCollection;

class TypesGenerator implements LookupDataGeneratorInterface
{
    /**
     * Custom sorting of the card types by their "group" property
     *
     * @var array
     */
    private $groupSorting = [
        "general type"     => 1,
        "current"          => 2,
        "flag"             => 3,
        "magic stone type" => 4,
        "legacy"           => 5,
    ];

    /**
     * This array defines the rules to build the "UI" or "colloquial" types,
     * Which could map to proper types, pseudo-types, a collection of types etc.
     * 
     * Each element is a list of all bit positions composing the UI type
     * Bit positions can be read from "card_types" table
     * Order is important and mantained on the UI also
     *
     * @var array
     */
    private $displayTypes = [
        [0],     // Ruler
        [1],     // J-Ruler,
        [9, 0],  // Basic Ruler
        [9, 1],  // Basic J-Ruler
        [3],     // Resonator
        [4],     // Chant
        [5],     // Addition
        [6],     // Regalia
        [7],     // Rune
        [25, 7], // Master Rune
        [1],     // Magic Stone
        [9, 2],  // Basic Magic Stone
        [8, 2],  // Special Magic Stone

        [10],    // Light Magic Stone
        [11],    // Fire Magic Stone
        [12],    // Water Magic Stone
        [13],    // Wind Magic Stone
        [14],    // Darkness Magic Stone

        [15],    // Spell: Chant
        [16],    // Spell: Chant-Instant
        [17],    // Spell: Chant-Standby
        [18],    // Addition: Field
        [19],    // Addition: Resonator
        [20],    // Addition: Ruler/J-Ruler
        [21],    // Addition: J/Resonator
        [22],    // True Magic Stone
    ];

    private function sortCardTypes(ItemsCollection $collection): ItemsCollection
    {
        $groups = $this->groupSorting;
        return $collection->transformThisCollection()->sort(
            function ($a, $b) use (&$groups) {
                if ($groups[$a->group] !== $groups[$b->group]) {
                    return $groups[$a->group] - $groups[$b->group];
                }
                return $a->bit - $b->bit;
            }
        );
    }

    public function generate(): object
    {
        $result = (object) [
            "bit2name" => new \stdClass(),
            "name2bit" => new \stdClass(),
            "display"  => new \stdClass(),
        ];

        $repository = EntityManager::getRepository(CardType::class);
        $collection = $repository->all();
        $collection = $this->sortCardTypes($collection);

        foreach ($collection as $item) {
            $result->bit2name->{$item->bit} = $item->name;
            $result->name2bit->{$item->name} = $item->bit;
        }

        $result->display = $this->buildDisplayTypes($result->bit2name);

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
     * @param object $map BIT => NAME map
     * @return object
     */
    private function buildDisplayTypes(object $bit2name): object
    {
        $result = new \stdClass();

        foreach ($this->displayTypes as $bits) {
            $names = [];
            $mask = 0;
            foreach ($bits as $bit) {
                $bitString = (string) $bit;
                $names[] = $bit2name->{$bit};
                $mask |= (1 << $bit);
            }
            $name = implode(" ", $names);

            // Add UI card type
            $result->{$name} = $mask;
        }

        return $result;
    }
}
