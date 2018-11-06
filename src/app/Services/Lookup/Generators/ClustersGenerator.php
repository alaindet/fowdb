<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class ClustersGenerator implements Generatable
{
    public function generate(): array
    {
        $items = database_old()->get(
            "SELECT
                c.code cluster_slug,
                c.name cluster_label,
                s.name set_label,
                s.code set_slug
            FROM
                sets s
                INNER JOIN clusters c ON s.clusters_id = c.id
            ORDER BY
                c.id DESC,
                s.id DESC
            "
        );

        $cacheCluster = '';

        return array_reduce(
            $items,
            /**
             * IMPORTANT NOTE HERE:
             * $cacheCluster is imported by reference using &
             * This is the ONLY way to manipulate an external variable
             * from inside a closure!
             */
            function ($result, $item) use (&$cacheCluster) {

                // Update the cached value
                if ($cacheCluster !== $item['cluster_slug']) {
                    $cacheCluster = $item['cluster_slug'];
                    $result[$cacheCluster] = [
                        'name' => $item['cluster_label'],
                        'sets' => []
                    ];
                }

                // Store the set
                $sets =& $result[$cacheCluster]['sets'];
                $sets[ $item['set_slug'] ] = $item['set_label'];

                return $result;
            },
            []
        );
    }
}
