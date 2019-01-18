<?php

namespace App\Views;

use App\Utils\Arrays;
use App\Models\PlayRestriction as Model;

class PlayRestriction
{
    public static function display(
        array &$items,
        string $page = null
    ): array
    {
        $result = [];

        foreach ($items as $item) {

            Arrays::addNested(

                // Array
                $result,

                // Value
                [
                    'name' => $item['card_name'],
                    'code' => $item['card_code'],
                    'image' => asset($item['card_image'], 'jpg'),
                    'link' => url('card/'.urlencode($item['card_code']))
                ],

                // Levels
                $item['format_name'],
                Model::$decksLabels[$item['restriction_deck']],
                Model::$copiesLabels[$item['restriction_copies']]

            );
        }

        return $result;
    }

    public static function buildFiltersLabels(
        array &$filters,
        array &$item
    ): void
    {
        if (empty($filters)) return;
        
        if (array_key_exists('card', $filters)) {
            $filters['card'] = "{$item['card_name']} ({$item['card_code']})";
        }

        if (array_key_exists('format', $filters)) {
            $filters['format'] = $item['format_name'];
        }

        if (array_key_exists('deck', $filters)) {
            $filters['deck'] = Model::$decksLabels[$item['deck']];
        }

        if (array_key_exists('copies', $filters)) {
            $filters['copies'] = Model::$copiesLabels[$item['copies']];
        }
    }
}
