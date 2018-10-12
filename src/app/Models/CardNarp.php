<?php

namespace App\Models;

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
class CardNarp
{
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
            "SELECT id FROM cards WHERE narp = 0 AND cardname = :name LIMIT 1",
            [':name' => $name]
        );

        return (int) $cards[0]['id'];
    }

    /**
     * Returns the base print card having this exact name
     * Ex.: [ name=>, code=>, print=> ]
     * 
     * @param string $name The exact card name
     * @return array
     */
    public static function getBaseCard(string $name): array
    {
        $card = database()->get(
            "SELECT narp, cardocode
            FROM cards
            WHERE narp = 0 AND cardname = :name",
            [':name' => $name],
            $first = true
        );

        return empty($card) ? [] : [
            'name' => $name,
            'code' =>$card['cardcode'],
            'print' => self::$map[0]
        ];
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
            "SELECT narp, cardcode
            FROM cards
            WHERE narp > 0 AND cardname = :name
            ORDER BY 'block' DESC, setnum DESC, cardnum DESC",
            [':name' => $name]
        );

        return array_reduce($cards, function ($result, $card) {
            $title = self::$map[$card['narp']].'s';
            if (!isset($result[$title])) $result[$title] = [];
            $result[$title][] = $card['cardcode'];
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
                'cards' => [ self::getBaseCard($name) ]
            ];
        }
    }
}
