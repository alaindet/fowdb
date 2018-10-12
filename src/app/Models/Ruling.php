<?php

namespace App\Models;

class Ruling
{
    public static function getByCardId(
        string $cardId,
        bool $render = false
    ): array
    {
        $data = database()->get(
            "SELECT id, created, is_edited, is_errata, ruling
            FROM rulings
            WHERE cards_id = :id
            ORDER BY is_errata DESC, created DESC",
            [':id' => $cardId]
        );

        // Do not render ruling text as HTML
        if (!$render) return $data;

        // Render ruling text as HTML
        return array_map(function ($ruling) {
            $ruling['ruling'] = render($ruling['ruling']);
		    return $ruling;
        }, $data);
    }
}
