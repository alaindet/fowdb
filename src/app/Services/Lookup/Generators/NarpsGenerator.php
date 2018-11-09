<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Models\CardNarp;

class NarpsGenerator implements Generatable
{
    public function generate(): array
    {
        $items = (new CardNarp)->all();

        $result = [
            'id2code' => [],
            'id2name' => []
        ];

        return array_reduce($items, function ($result, $item) {

            $result['id2code'][$item['value']] = $item['code'];
            $result['id2name'][$item['value']] = $item['name'];

            return $result;

        }, $result);
    }
}
