<?php

namespace App\Services\Lookup\Generators;

use App\Services\Lookup\Generatable;

class FormatsGenerator implements Generatable
{
    public function data(): array
    {
        return database()
            ->select(
                statement("select")
                    ->select([
                        "f.id AS f_id",
                        "f.bit AS f_bit",
                        "f.name AS f_name",
                        "f.code AS f_code",
                        "f.is_default AS f_is_default",
                        "f.is_multi_cluster AS f_is_multi_cluster",
                        "c.id AS c_id",
                    ])
                    ->from(
                        "game_formats AS f
                        INNER JOIN pivot_cluster_format AS cf ON f.id = cf.formats_id
                        INNER JOIN game_clusters AS c ON cf.clusters_id = c.id"
                    )
                    ->orderBy([
                        "f.is_multi_cluster DESC",
                        "f.id DESC",
                        "c.id DESC",
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

                if ($i["f_is_default"]) {
                    $o["default"] = $i["f_code"];
                }

                $o["code2id"][$i["f_code"]] = $i["f_id"];
                $o["code2name"][$i["f_code"]] = $i["f_name"];

                if (!isset($o["code2clusters"][$i["f_code"]])) {
                    $o["code2clusters"][$i["f_code"]] = [];
                }

                $o["code2clusters"][$i["f_code"]][] = $i["c_id"];
                $o["id2code"][$i["f_id"]] = $i["f_code"];
                $o["id2name"][$i["f_id"]] = $i["f_name"];
                $o["code2bit"][$i["f_code"]] = $i["f_bit"];
                $o["bit2name"][$i["f_bit"]] = $i["f_name"];

                return $o;
            },
        
            // State
            [
                "default" => "",
                "code2id" => [],
                "code2name" => [],
                "code2clusters" => [],
                "id2code" => [],
                "id2name" => [],
                "code2bit" => [],
                "bit2name" => [],
            ]

        );
    }
}
