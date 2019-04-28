<?php

namespace App\Entity\GameCluster;

use App\Base\Entity\Repository\EntityRepository;
use App\Base\Items\ItemsCollection;
use App\Entity\GameCluster\GameCluster;
use App\Entity\GameFormat\GameFormat;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;

class GameClusterRepository extends EntityRepository
{
    public $table = "game_clusters";
    public $tableAlias = "c";

    /**
     * Returns all formats containing given cluster
     *
     * @param GameCluster $cluster
     * @param array $formatFields
     * @return ItemsCollection
     */
    public function getFormats(
        GameCluster $cluster,
        array $formatFields = null
    ): ItemsCollection
    {
        $formatAllowedFields = [
            "id",
            "code",
            "name",
            "desc",
            "is_default",
            "is_multi_cluster",
        ];

        if ($formatFields !== null) {
            $fields = array_intersect($formatAllowedFields, $formatFields);
        } else {
            $fields = &$formatAllowedFields;
        }

        foreach ($fields as &$field) {
            $field = "f.{$field}";
        }

        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from($this->table, $this->tableAlias)
            ->innerJoin(["pivot_cluster_format", "cf"], "clusters_id", "id")
            ->innerJoin(["game_formats", "f"], "id", "formats_id")
            ->where("{$this->tableAlias}.id = :id")
            ->orderBy("f.id");

        $items = Database::getInstance()
            ->select($statement)
            ->bind([":id" => $cluster->id])
            ->get(GameCluster::class);

        return (new ItemsCollection)->set($items);
    }
}
