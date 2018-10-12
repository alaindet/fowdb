<?php

namespace App\Models;

class Card
{
    public static function getByCode(string $code): array
    {
        return database()->get(
            "SELECT *
            FROM cards
            WHERE cardcode = :code
            LIMIT 3",
            [':code' => $code]
        );
    }
}
