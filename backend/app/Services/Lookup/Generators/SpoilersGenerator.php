<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class SpoilersGenerator implements Generatable
{
    public function generate(): array
    {
        $items = database()
            ->select(statement('select')
                ->select(['id', 'code', 'name', 'count'])
                ->from('game_sets')
                ->where('is_spoiler = 1')
                ->orderBy('id DESC')
            )
            ->get();

        return array_reduce(

            // Collections
            $items,

            // Reducer
            function ($result, $item) {
                $result['sets'][] = $item;
                $result['ids'][] = $item['id'];
                $result['names'][] = $item['name'];
                $result['codes'][] = $item['code'];
                $result['counts'][] = $item['count'];
                return $result;
            },

            // State
            [
                'sets' => [],
                'ids' => [],
                'names' => [],
                'codes' => [],
                'counts' => []
            ]

        );
    }
}
