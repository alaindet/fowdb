<?php

namespace App\Views\Entities;

class Ruling
{
    /**
     * Builds labels for GET parameters filtering a collection of resources
     *
     * @param array $filters
     * @param array $item
     * @return void
     */
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
            $filters['deck'] = self::$decksLabels[$item['deck']];
        }

        if (array_key_exists('copies', $filters)) {
            $filters['copies'] = self::$copiesLabels[$item['copies']];
        }
    }

}
