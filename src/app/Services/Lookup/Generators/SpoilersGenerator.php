<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class SpoilersGenerator implements Generatable
{
    public function generate(): array
    {
        $items = database()->get(
            "SELECT
                code,
                name,
                count
            FROM
                sets
            WHERE
                isspoiler = 1
            ORDER BY
                id DESC"
        );

        // Is this useless?
        return array_reduce($items, function ($result, $item) {

            $result[] = [
                'name' => $item['name'],
                'code' => $item['code'],
                'count' => $item['count']
            ];

            return $result;

        }, []);
    }
}
