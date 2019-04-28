<?php

namespace App\Entity\GameCluster;

use App\Base\Entity\EntityRepository;
use App\Services\Database\Database;
use App\Services\Database\Statement\SelectSqlStatement;
use App\Base\Items\ItemsCollection;
use App\Base\Entity\GameFormat\GameFormat;
use App\Base\Entity\GameCluster\GameCluster;

class GameClusterRepository extends EntityRepository
{
    public $table = "game_clusters";

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
        $tables = [
            "left" => [
                "name" => $this->table,
                "alias" => "f",
                "field-to-middle" => "id",
            ],
            "middle" => [
                "name" => "pivot_cluster_format",
                "alias" => "cf",
                "field-to-left" => "format_id",
                "field-to-right" => "cluster_id",
            ],
            "right" => [
                "name" => "game_clusters",
                "alias" => "c",
                "field-to-middle" => "id",
            ],
        ];

        $allowedFields = [
            "id",
            "code",
            "name",
            "desc",
            "is_default",
            "is_multi_cluster",
        ];

        if ($formatFields !== null) {
            $fields = array_intersect($allowedFields, $formatFields);
        } else {
            $fields = &$allowedFields;
        }

        foreach ($fields as &$field) {
            $field = "f.{$field}";
        }

        $statement = (new SelectSqlStatement)
            ->select($fields)
            ->from("
                {$this->table} as f
                INNER JOIN pivot_cluster_format as cf ON cf.formats_id = f.id
                INNER JOIN game_clusters as c ON cf.clusters_id = c.id
            ")
            ->where("f.id = :id");

        $items = Database::getInstance()
            ->select($statement)
            ->bind([":id" => $format->id])
            ->get(GameCluster::class);

        return (new ItemsCollection)->set($items);
    }
}
