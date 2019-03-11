<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Interfaces\LookupDataGeneratorInterface;
use App\Models\CardRarity;

class RaritiesGenerator implements LookupDataGeneratorInterface
{
    public function generate(): array
    {
        $items = (new CardRarity)->all();

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
