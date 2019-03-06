<?php

namespace App\Entities\Play\Format;

use App\Entities\Game\Cluster\Cluster;
use App\Entities\Game\Cluster\Clusters;

trait FormatComputedPropertiesTrait
{
    protected function getClustersProperty(): Clusters
    {
        // Use cached data
        if ($this->useCache()) {
            $items = [];
            foreach (lookup("formats.id2clusters.{$this->id}") as $cluster) {
                $items[] = new Cluster($cluster);
            }
            $collection = (new Clusters)->set($items);
            return $collection;
        } 
    
        $statement = statement('select')
            ->select([
                'c.id AS id',
                'c.code AS code',
                'c.name AS name',
            ])
            ->from(
                'pivot_cluster_format AS cf
                INNER JOIN game_clusters AS c ON cf.clusters_id = c.id'
            )
            ->where('cf.formats_id = :id')
            ->orderBy('cf.clusters_id DESC');

        $items = database()
            ->select($statement)
            ->bind([':id' => $this->id])
            ->get(Cluster::class);

        $collection = (new Clusters)->set($items);

        return $collection;
    }

    protected function setClustersProperty($value)
    {
        return $value;
    }
}
