<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Models\CardBackSide;

class BackSidesGenerator implements Generatable
{
    public function generate(): array
    {
        $results = [
            'code2id' => [],
            'code2name' => [],
            'id2code' => [],
            'id2name' => [],
        ];

        foreach ((new CardBackSide)->all() as $item) {
            $results['code2id'][$item['code']] = $item['id'];
            $results['code2name'][$item['code']] = $item['name'];
            $results['id2code'][$item['id']] = $item['code'];
            $results['id2name'][$item['id']] = $item['name'];
        }

        return $results;
    }
}
