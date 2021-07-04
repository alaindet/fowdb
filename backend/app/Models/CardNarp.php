<?php

namespace App\Models;

use App\Base\Model;

/**
 * | Value | Abbrev | Desc          |
 * | ----- | ------ | ------------- |
 * | 0     | N      | Normal (base) |
 * | 1     | A      | Alternate Art |
 * | 2     | R      | Reprint       |
 * | 3     | P      | Promo         |
 * | 4     | M      | Memoria       |
 * 
 * Every card as a 'narp' flag whose value representing if it's a base card
 * or something else (alternate art, promo, reprint).
 * Since Force of Will TCG enforces uniqueness of cards by their name,
 * Given a card's name you can extract its base ID from the database
 */
class CardNarp extends Model
{
    public $table = 'card_narps';

    public static $id2name = [
        'Base Print',
        'Alternate Art',
        'Reprint',
        'Promo',
        'Memoria',
    ];

    /**
     * Returnes a card's base ID from its name.
     *
     * @param string $name
     * @return string
     */
    public static function getBaseIdByName(string $name): int
    {
        $item = database()
            ->select(
                statement('select')
                    ->select('id')
                    ->from('cards')
                    ->where('narp = 0')
                    ->where('name = :name')
                    ->limit(1)
            )
            ->bind([':name' => $name])
            ->first();

        return (int) $item['id'];
    }

    /**
     * Returns the card code of the base print having this exact name
     * 
     * @param string $name The exact card name
     * @return array
     */
    public static function getBaseCode(string $name): string
    {
        $item = database()
            ->select(
                statement('select')
                    ->select('code')
                    ->from('cards')
                    ->where('narp = 0')
                    ->where('name = :name')
                    ->limit(1)
            )
            ->bind([':name' => $name])
            ->first();
        
        return $item['code'];
    }

    /**
     * Returns an array with cards related to this name, grouped by print type
     * Does *not* include base prints
     * Ex.:
     * [
     *     'Reprints' => [ code, ... ],
     *     'Promos' => [ code, ... ],
     *      ...
     * ]
     *
     * @param string $name
     * @return array Related cards or []
     */
    public static function getRelatedCards(string $name): array
    {
        $items = database()
            ->select(
                statement('select')
                    ->select([
                        'narp',
                        'code'
                    ])
                    ->from('cards')
                    ->where('narp > 0')
                    ->where('name = :name')
                    ->orderBy([
                        'clusters_id DESC',
                        'sets_id DESC',
                        'num DESC'
                    ])

            )
            ->bind([':name' => $name])
            ->get();

        $results = [];

        foreach ($items as $item) {

            // Ex.: Alternate arts
            $title = self::$id2name[ $item['narp'] ] . 's';

            // Initialize the list if needed
            if (!isset($results[$title])) $results[$title] = [];

            // Add this card to the list
            $results[$title][] = $item['code'];

        }

        return $results;
    }

    /**
     * Returns all related cards based on NARP and name of a card
     * Ex.: [ flag=>, cards=> ]
     * 
     * | Flag | Meaning                  |
     * | ---- | ------------------------ |
     * | 0    | It's base, has *no* ARPs |
     * | 1    | It's base, has ARPs      |
     * | 2    | It's ARP                 |
     *
     * @param int $narp
     * @param string $name
     * @return array
     */
    public static function displayRelatedCards(
        int $narp,
        string $name
    ): array
    {
        // This is a base card
        if ($narp === 0) {

            // Ex.:
            // [
            //     'Reprints' => [ code, ... ],
            //     'Promos' => [ code, ... ],
            //      ...
            // ]
            $cards = self::getRelatedCards($name);
            
            return !empty($cards) // Has ARPs?
                ? [ 'flag' => 1, 'cards' => $cards ]
                : [ 'flag' => 0, 'cards' => [] ];
        }
        
        // This is a related card
        else {
            return [
                'flag' => 2,
                'cards' => [
                    'Base Print' => [ self::getBaseCode($name) ]
                ]
            ];
        }
    }
}
