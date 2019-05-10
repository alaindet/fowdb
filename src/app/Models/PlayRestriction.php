<?php

namespace App\Models;

use App\Exceptions\ModelException;
use App\Base\Model;

class PlayRestriction extends Model
{
    public $table = 'play_restrictions';

    public $numeric = [
        'id',
        'cards_id',
        'formats_id',
        'deck',
        'copies'
    ];

    private static $pageFunctions = [
        'banlist' => 'getBanlistPageData',
        'card' => 'getCardPageData',
        'restrictions/manage' => 'getBanlistPageData',
    ];

    private static $decks = [
        'Main Deck',
        'Side Deck',
        'Magic Stone Deck',
        'Rune Deck'
    ];

    public static $copiesLabels = [
        0 => 'Banned List',
        1 => 'Limited List'
    ];

    public static $decksLabels = [
        0 => 'Main Deck',
        1 => 'Side Deck',
        2 => 'Magic Stone Deck',
        3 => 'Rune Deck'
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
        return fd_database()
            ->select(
                statement('select')
                    ->select([
                        'f.name format_name',
                        'f.code format_code',
                        'c.name card_name',
                        'c.code card_code',
                        'c.thumb_path card_image',
                        'r.deck restriction_deck',
                        'r.copies restriction_copies',
                    ])
                    ->from(
                        'play_restrictions r
                        INNER JOIN cards c ON r.cards_id = c.id
                        INNER JOIN game_formats f ON r.formats_id = f.id'        
                    )
                    ->where('c.narp = 0')
                    ->orderBy([
                        'r.copies ASC',
                        'r.deck ASC',
                        'f.is_multi_cluster DESC',
                        'f.id DESC',
                        'c.clusters_id DESC',
                        'c.sets_id DESC',
                        'c.num ASC',
                    ])
            )
            ->get();
    }

    public static function getCardPageData(array $args): array
    {
        // ERROR: Missing arguments
        if (!isset($args[0])) {
            throw new ModelException('Missing arguments');
        }

        $items = fd_database()
            ->select(
                statement('select')
                    ->select([
                        'f.name as name',
                        'r.deck as deck',
                        'r.copies as copies',
                    ])
                    ->from('
                        play_restrictions r
                        INNER JOIN game_formats f ON r.formats_id = f.id'
                    )
                    ->where('cards_id = :id')
                    ->orderBy([
                        'r.deck ASC',
                        'f.id ASC',
                    ])
            )
            ->bind([':id' => $args[0]])
            ->get();

        $results = [];

        foreach ($items as $item) {
            
            // Build label for banned deck (if not Main Deck)
            $deck = null;
            if ($item['deck'] > 0) {
                $deck = self::$decksLabels[$item['deck']];
            }

            // Build label for copies (if more than zero)
            $copies = null;
            if ($item['copies'] > 0) {
                $n = $item['copies'];
                $copies = "{$n} cop".($n > 1 ? 'ies' : 'y');
            }

            // Add labels to the results
            $results[] = [
                'format' => $item['name'],
                'deck' => $deck,
                'copies' => $copies
            ];

        }

        return $results;
    }       
}
