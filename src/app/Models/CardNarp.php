<?php

namespace App\Models;

use App\Base\Model;

/**
 * | Value | Abbrev | Desc          |
 * | ----- | ------ | ------------- |
 * | 0     | N      | Normal (base) |
 * | 1     | A      | Alternate art |
 * | 2     | R      | Reprint       |
 * | 3     | P      | Promo         |
 * 
 * Every card as a 'narp' flag whose value representing if it's a base card
 * or something else (alternate art, promo, reprint).
 * Since Force of Will TCG enforces uniqueness of cards by their name,
 * Given a card's name you can extract its base ID from the database
 */
class CardNarp extends Model
{
    public $table = 'card_narps';

    public static $map = [
        'Base Print',
        'Alternate art',
        'Reprint',
        'Promo'
    ];

    /**
     * Returnes a card's base ID from its name.
     *
     * @param string $name
     * @return string
     */
    public static function getBaseIdByName(string $name): int
    {
        $cards = database()->get(
            "SELECT id FROM cards WHERE narp = 0 AND name = :name LIMIT 1",
            [':name' => $name]
        );

        return (int) $cards[0]['id'];
    }

    /**
     * Returns the card code of the base print having this exact name
     * 
     * @param string $name The exact card name
     * @return array
     */
    public static function getBaseCode(string $name): string
    {
        return database()->get(
            "SELECT narp, code
            FROM cards
            WHERE narp = 0 AND name = :name
            LIMIT 1",
            [':name' => $name]
        )[0]['code'];
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
        $cards = database()->get(
            "SELECT narp, code
            FROM cards
            WHERE narp > 0 AND name = :name
            ORDER BY clusters_id DESC, sets_id DESC, num DESC",
            [':name' => $name]
        );

        return array_reduce($cards, function ($result, $card) {
            $title = self::$map[$card['narp']].'s';
            if (!isset($result[$title])) $result[$title] = [];
            $result[$title][] = $card['code'];
            return $result;
        }, []);
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
