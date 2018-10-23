<?php

namespace App\Views;

use App\Utils\Arrays;

class Ban
{
    public static function display(array &$items, string $page): array
    {
        $result = [];

        $map = [
            'copies' => [
                'Banned List',
                'Limited List'
            ],
            'deck' => [
                'Main Deck',
                'Side Deck',
                'Magic Stone Deck',
                'Rune Deck'
            ]
        ];

        foreach ($items as $item) {

            Arrays::addNested(

                // Array
                $result,

                // Value
                [
                    'name' => $item['card_name'],
                    'code' => $item['card_code'],
                    'image' => asset($item['card_image'], 'jpg'),
                    'link' => url_old('card', [
                        'code' => urlencode($item['card_code'])
                    ])
                ],

                // Levels
                $map['copies'][ $item['ban_copies'] ],
                $map['deck'][ $item['ban_deck'] ],
                $item['format_name']

            );
        }

        return $result;
    }
}
