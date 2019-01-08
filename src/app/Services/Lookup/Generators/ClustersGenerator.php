<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class ClustersGenerator implements Generatable
{
    public function generate(): array
    {
        $current = '';

        return array_reduce(

            // Items
            database()
            ->select(
                statement('select')
                    ->select([
                        'c.id c_id',
                        'c.code c_code',
                        'c.name c_name',
                        's.name s_name',
                        's.code s_code',
                    ])
                    ->from(
                        'game_sets s
                        INNER JOIN game_clusters c ON s.clusters_id = c.id'
                    )
                    ->orderBy([
                        'c.id DESC',
                        's.id DESC'
                    ])
            )
            ->get(),

            /**
             * Reducer function
             * 
             * IMPORTANT NOTE HERE:
             * $current is imported by reference using &
             * This is the ONLY way to manipulate an external variable
             * from inside a closure!
             */
            function ($result, $item) use (&$current) {

                // Populate id => name map
                $result['id2name'][$item['c_id']] = $item['c_name'];

                // Populate code => id map
                $result['code2id'][$item['c_code']] = $item['c_id'];

                // Break the cached value
                if ($current !== $item['c_code']) {

                    // Update the cached value
                    $current = $item['c_code'];

                    // Initialize a new cluster into the list
                    $result['list'][$current] = [
                        'name' => $item['c_name'],
                        'sets' => []
                    ];
                }

                // Store the set into the list
                $sets =& $result['list'][$current]['sets'];
                $sets[ $item['s_code'] ] = $item['s_name'];

                return $result;
            },

            // Initial state
            [
                'list' => [],
                'id2name' => [],
                'code2id' => [],
            ]
        );
    }
}
