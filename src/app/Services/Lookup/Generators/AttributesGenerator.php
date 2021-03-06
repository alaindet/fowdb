<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Models\CardAttribute;

class AttributesGenerator implements Generatable
{
    public function generate(): array
    {
        $result = [
            'bit2code'  => [],
            'bit2name'  => [],
            'code2bit'  => [],
            'code2name' => [],
            'name2bit'  => [],
            'name2code' => [],
            'display'   => [],
        ];

        foreach ((new CardAttribute)->all() as $item) {
            $result['bit2code'][$item['bit']]  = $item['code'];
            $result['bit2name'][$item['bit']]  = $item['name'];
            $result['code2bit'][$item['code']] = $item['bit'];
            $result['code2name'][$item['code']] = $item['name'];
            $result['name2bit'][$item['name']]  = $item['bit'];
            $result['name2code'][$item['name']] = $item['code'];

            if ($item['display']) {
                $result['display'][$item['code']] = $item['name'];
            }
        }

        return $result;
    }
}
