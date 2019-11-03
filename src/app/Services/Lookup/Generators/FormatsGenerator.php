<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class FormatsGenerator implements Generatable
{
    public function data(): array
    {
        return database()
            ->select(
                statement('select')
                    ->select([
                        'f.id f_id',
                        'f.name f_name',
                        'f.code f_code',
                        'f.is_default f_is_default',
                        'f.is_multi_cluster f_is_multi_cluster',
                        'c.id c_id',
                    ])
                    ->from(
                        'game_formats f
                        INNER JOIN pivot_cluster_format cf ON f.id = cf.formats_id
                        INNER JOIN game_clusters c ON cf.clusters_id = c.id'
                    )
                    ->orderBy([
                        'f.is_multi_cluster DESC',
                        'f.id DESC',
                        'c.id DESC',
                    ])
            )
            ->get();
    }


    public function generate(): array
    {
        return array_reduce(
            
            // Data
            $this->data(),
            
            // Reducer
            function ($o, $i) {

                if ($i['f_is_default']) $o['default'] = $i['f_code'];
                $o['code2id'][$i['f_code']] = $i['f_id'];
                $o['code2name'][$i['f_code']] = $i['f_name'];

                if (!isset($o['code2clusters'][$i['f_code']])) {
                    $o['code2clusters'][$i['f_code']] = [];
                }
                $o['code2clusters'][$i['f_code']][] = $i['c_id'];

                $o['id2code'][$i['f_id']] = $i['f_code'];
                $o['id2name'][$i['f_id']] = $i['f_name'];

                return $o;
            },
        
            // State
            [
                'default' => '',
                'code2id' => [],
                'code2name' => [],
                'code2clusters' => [],
                'id2code' => [],
                'id2name' => [],
            ]

        );
    }
}
