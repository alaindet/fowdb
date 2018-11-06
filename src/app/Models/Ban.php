<?php

namespace App\Models;
use App\Exceptions\ModelException;

class Ban
{
    private static $pageFunctions = [
        'banlist' => 'getBanlistPageData',
        'card' => 'getCardPageData'
    ];

    private static $decks = [
        'Main Deck',
        'Side Deck',
        'Magic Stone Deck',
        'Rune Deck'
    ];

    public static function getData(string $page, ...$theRest): array
    {
        // ERROR: Invalid page
        if (!in_array($page, array_keys(self::$pageFunctions))) {
            throw new ModelException(
                "Missing function for page \"{$page}\""
            );
        }

        $function = self::$pageFunctions[$page];
        return self::$function($theRest);
    }

    public static function getBanlistPageData(): array
    {
        return database_old()->get(
            "SELECT
                f.name format_name,
                f.code format_code,
                c.name card_name,
                c.code card_code,
                c.thumb_path card_image,
                b.deck ban_deck,
                b.copies ban_copies
            FROM
                bans b
                INNER JOIN cards c ON b.cards_id = c.id
                INNER JOIN formats f ON b.formats_id = f.id
            WHERE
                c.narp = 0
            ORDER BY
                b.copies ASC,
                b.deck ASC,
                f.is_multi_cluster DESC,
                f.id DESC,
                c.clusters_id DESC,
                c.sets_id DESC,
                c.num ASC"
        );
    }

    public static function getCardPageData(array $args): array
    {
        // ERROR: Missing arguments
        if (!isset($args[0])) {
            throw new ModelException('Missing arguments');
        }

        $id = $args[0];

        $items = database_old()->get(
            "SELECT
                f.name as name,
                b.deck as deck,
                b.copies as copies
            FROM
                bans b
                INNER JOIN formats f ON b.formats_id = f.id
            WHERE
                cards_id = :id
            ORDER BY
                b.deck ASC,
                f.id ASC",
            [':id' => $id]
        );

        return array_map(function ($i) {
            $format = $i['name'];
            $deck = ($i['deck'] > 0) ? self::$decks[$i['deck']] : null;
            $n = $i['copies'];
            $copies = ($n > 0) ? "{$i} cop".($i > 1 ? 'ies' : 'y') : null;
            return compact('format', 'deck', 'copies');
        }, $items);
    }       
}
