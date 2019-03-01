<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Models\PlayRestriction;

class BannedGenerator implements Generatable
{
    public function generate(): array
    {
        $result = [];
        $items = database()
            ->select(statement('select')
                ->select([
                    'pr.id AS id',
                    'pr.cards_id AS cards_id',
                    'gf.code AS format_code',
                    'pr.deck AS deck',
                    'pr.copies AS copies'
                ])
                ->from(
                    'play_restrictions pr
                    INNER JOIN game_formats gf ON pr.formats_id = gf.id'
                )
                ->where(
                    'gf.is_multi_cluster = 1'
                )
                ->orderBy([
                    'pr.formats_id DESC',
                    'pr.cards_id ASC',
                    'pr.deck ASC',
                    'pr.copies ASC'
                ])
            )
            ->get();

        // STRUCTURE
        // =========
        // [
        //     format_code => [
        //         card_id,
        //         ...
        //     ],
        //     ...
        // ]
        foreach ($items as $item) {
            
            // Format
            $format = &$item['format_code'];
            if (!isset($result[$format])) {
                $result[$format] = [];
            }

            // Card
            $card = &$item['cards_id'];
            if (!in_array($card, $result[$format])) {
                $result[$format][] = $card;
            }
            
        }

        // // STRUCTURE
        // // =========
        // // [
        // //     format_code => [
        // //         card_id => [
        // //             deck_name => copies,
        // //             ...
        // //         ],
        // //         ...
        // //     ],
        // //     ...
        // // ]
        // foreach ($items as $item) {

        //     // Format
        //     $format = &$item['format_code'];
        //     if (!isset($result[$format])) {
        //         $result[$format] = [];
        //     }

        //     // Card
        //     $card = &$item['cards_id'];
        //     if ((!isset($result[$format][$card]))) {
        //         $result[$format][$card] = [];
        //     }

        //     // Deck => copies
        //     $deckId = &$item['deck'];
        //     $deck = PlayRestriction::$decksLabels[$deckId];
        //     $deck = str_replace(' Deck', '', $deck); // 'Main Deck' => 'Main'
        //     $copies = &$item['copies'];
        //     $result[$format][$card][$deck] = $copies;

        // }

        return $result;
    }
}
