<?php

namespace App\Http\Controllers;

use App\Base\Controller;
use App\Http\Request\Request;
use App\Views\Page;
use App\Utils\Bitmask;

/**
 * RACES::amount
 * select race, count(id) amount
 * from cards
 * group by race having race is not null
 * order by amount desc, race asc
 * 
 * RACES::alpha
 * select race, count(id) amount
 * from cards
 * group by race having race is not null
 * order by race asc
 *
 */
class RacesController extends Controller
{
    private $defaultSort = "most-used";

    /**
     * Sortable fields
     * 
     * KEY => [ORDER BY CLAUSE, LABEL]
     *
     * @var array
     */
    private $sortables = [
        "most-used" => "Sort by: most used",
        "alphabetically" => "Sort by: alphabetically"
    ];

    /**
     * Fetched the items from the database, accepts sorting
     *
     * @param string $what Values: "races", "traits"
     * @param string $sort Values: "most-used", "alphabetically"
     * @return array Results from the database
     */
    private function getItems(string $what, string $sort): array
    {
        $filter = $this->buildRaceFilter($what === "races");

        $data = fd_database()
            ->select(
                fd_statement("select")
                    ->fields("race")
                    ->fields("count(id) amount")
                    ->fields("type_bit")
                    ->from("cards")
                    ->groupBy("race")
                    ->having("race IS NOT NULL")
                    ->having($filter)
            )
            ->get();
        
        $items = [];
        foreach ($data as $item) {
            foreach (explode("/", $item["race"]) as $value) {
                if (!isset($items[$value])) $items[$value] = 0;
                $items[$value] += $item["amount"];
            }
        }

        // Sorting: most-used
        if ($sort === "most-used") arsort($items);

        // Sorting: alphabetically
        elseif ($sort === "alphabetically") ksort($items);

        return $items;
    }

    private function buildRaceFilter(bool $matchFields = true): string
    {
        $types = ["Ruler", "J-Ruler", "Resonator"];
        $map = fd_lookup("types.display");

        $bitmask = new Bitmask;
        foreach ($types as $type) $bitmask->addBitValue($map[$type]);
        $typeValue = $bitmask->getMask();

        $operator = ($matchFields) ? ">" : "=";

        return "type_bit & {$typeValue} {$operator} 0";
    }

    public function index(Request $request): string
    {
        $input = $request->input();
        $sortRaces = $input->get("sort-races") ?? $this->defaultSort;
        $sortTraits = $input->get("sort-traits") ?? $this->defaultSort;

        return (new Page)
            ->template("pages/public/races/index")
            ->title("Races and Traits")
            ->variables([
                "races" => $this->getItems("races", $sortRaces),
                "traits" => $this->getItems("traits", $sortTraits),
                "sortables" => $this->sortables,
                "sort_races" => $sortRaces,
                "sort_traits" => $sortTraits
            ])
            ->render();
    }
}
