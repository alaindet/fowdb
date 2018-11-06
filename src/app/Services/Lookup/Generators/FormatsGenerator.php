<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class FormatsGenerator implements Generatable
{
    public function generate(): array
    {
        $items = database_old()->get(
            "SELECT *
            FROM formats
            WHERE is_multi_cluster = 1"
        );

        $result = [
            'default' => '',
            'code2id' => [],
            'code2name' => [],
            'id2code' => [],
            'id2name' => [],
        ];

        return array_reduce($items, function ($result, $item) {

            if ($item['is_default']) $result['default'] = $item['code'];
            $result['code2id'][$item['code']] = $item['id'];
            $result['code2name'][$item['code']] = $item['name'];
            $result['id2code'][$item['id']] = $item['code'];
            $result['id2name'][$item['id']] = $item['name'];

            return $result; 

        }, $result);
    }
}
