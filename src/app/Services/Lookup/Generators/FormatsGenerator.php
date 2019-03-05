<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Entities\Play\Format\FormatsRepository;

class FormatsGenerator implements Generatable
{
    public function generate(): array
    {
        $state = [
            'default' => '',
            'code2id' => [],
            'code2name' => [],
            'code2clusters' => [],
            'id2code' => [],
            'id2name' => [],
            'display' => [],
            'id2clusters' => []
        ];

        $reducer = function($result, $format) {
            
            // Set the cache flag: invalidate the cache!
            // This forces any computed property to call the database
            $format->useCache(false);

            if ($format->is_default) {
                $result['default'] = $format->code;
            }

            if ($format->is_multi_cluster) {
                $result['display'][$format->code] = $format->name;
            }

            $result['code2id'][$format->code] = $format->id;
            $result['code2name'][$format->code] = $format->name;
            $result['id2code'][$format->id] = $format->code;
            $result['id2name'][$format->id] = $format->name;

            $result['id2clusters'][$format->id] = $format->clusters
                ->reduce(
                    function($result, $cluster) {
                        $result[] = [
                            'id' => $cluster->id,
                            'code' => $cluster->code,
                            'name' => $cluster->name
                        ];
                        return $result;
                    },
                    $clusters = []
                );

            return $result;
        };

        return (new FormatsRepository)->all()->reduce($reducer, $state);
    }
}
