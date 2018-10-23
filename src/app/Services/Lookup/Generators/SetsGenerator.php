<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Models\CardSet;

class SetsGenerator implements Generatable
{
    public function generate(): array
    {
        $items = (new CardSet)->all();

        $result = [
            'code2id'   => [],
            'code2name' => [],
            'id2code'   => [],
            'id2name'   => [],
        ];

        return array_reduce($items, function ($result, $item) {
            
            $result['code2id'][$item['code']] = $item['id'];
            $result['code2name'][$item['code']] = $item['name'];
            $result['id2code'][$item['id']] = $item['code'];
            $result['id2name'][$item['id']] = $item['name'];

            return $result;

        }, $result);
    }
}
