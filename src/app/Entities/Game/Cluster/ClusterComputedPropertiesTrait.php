<?php

namespace App\Entities\Game\Cluster;

use App\Entities\Play\Format\Formats;
use App\Entities\Play\Format\Format;
use App\Entities\Game\Set\Sets;
use App\Entities\Game\Set\SetsRepository;

trait ClusterComputedPropertiesTrait
{
    protected function getFormatsProperty(): Formats
    {
        // Use cached data
        if ($this->useCache()) {
            $items = [];
            foreach (lookup("clusters.id2formats.{$this->id}") as $format) {
                $items[] = new Format($format);
            }
        }
    
        // Fetch data directly from database
        else {
            $statement = statement('select')
                ->select([
                    'f.id AS id',
                    'f.code AS code',
                    'f.name AS name',
                ])
                ->from(
                    'pivot_cluster_format AS cf
                    INNER JOIN game_formats AS f ON cf.formats_id = f.id'
                )
                ->where('cf.clusters_id = :id')
                ->orderBy('cf.formats_id');

            $items = database()
                ->select($statement)
                ->bind([':id' => $this->id])
                ->get(Format::class);
        }

        $collection = (new Formats)->set($items);
        return $collection;
    }

    protected function getSetsProperty(): Sets
    {
        $setsRepo = new SetsRepository;
        return $setsRepo->findAllByClusterId($this->id);
    }
}
