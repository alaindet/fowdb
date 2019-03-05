<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;
use App\Entities\Game\Cluster\ClustersRepository;

class ClustersGenerator implements Generatable
{
    public function generate(): array
    {
        $state = [
            'list' => [],
            'code2name' => [],
            'code2id' => [],
            'id2code' => [],
            'id2name' => [],
            'id2formats' => [],
        ];

        $reducer = function ($result, $cluster) {

            $cluster->useCache(false);

            $formats = $cluster->formats->reduce(function($result, $format) {
                $result[] = [
                    'id' => $format->id,
                    'code' => $format->code,
                    'name' => $format->name,
                ];
                return $result;
            }, []);
            
            $sets = $cluster->sets->reduce(function($result, $set) {
                $result[$set->code] = $set->name;
                return $result;
            }, []);

            $result['id2code'][$cluster->id] = $cluster->code;
            $result['id2name'][$cluster->id] = $cluster->name;
            $result['code2name'][$cluster->code] = $cluster->name;
            $result['code2id'][$cluster->code] = $cluster->id;
            $result['id2formats'][$cluster->id] = $formats;
            $result['list'][$cluster->code] = [
                'name' => $cluster->name,
                'sets' => $sets
            ];

            return $result;

        };

        return (new ClustersRepository)->all()->reduce($reducer, $state);
    }
}
