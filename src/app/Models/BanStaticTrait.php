<?php

namespace App\Models;

trait BanStaticTrait
{
    /**
     * Returns a list of formats in which this card is banned
     * Empty array is card is not banned in any format
     *
     * @param integer $cardId
     * @return array [ [format_name, deck_name, copies_in_deck], ... ]
     */
    public static function formatsList(int $cardId): array
    {
        $data = database()->get(
            "SELECT
                formats.name as name,
                bans.deck as deck,
                bans.copies as copies
            FROM bans INNER JOIN formats ON bans.formats_id = formats.id
            WHERE bans.cards_id = :id
            ORDER BY bans.deck ASC, formats.id",
            [':id' => $cardId]
        );

        // ERROR: No 
        if (empty($data)) return [];

        return array_map(function ($f) {
            $format = $f['name'];
            $deck = ($f['deck'] > 0) ? self::$decks[$f['deck']] : null;
            $n = $f['copies'];
            $copies = ($n > 0) ? "{$n} cop".($n > 1 ? 'ies' : 'y') : null;
            return compact('format', 'deck', 'copies');
        }, $data);
    }
}
