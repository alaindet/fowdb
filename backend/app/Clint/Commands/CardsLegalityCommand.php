<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\FileSystem\FileSystem;
use App\Utils\Time;

class CardsLegalityCommand extends Command
{
    public $name = "cards:legality";

    /**
     * Run the Clint command cards:legality
     *
     * Ex.:
     * $ php clint cards:legality
     *
     * @return CardsLegalityCommand
     */
    public function run(): Command
    {
        $ts = Time::timestamp();
        $sql = "/* {$ts} */\n\n";

        $rawFormatsStatement = statement("select")
            ->select([
                "f.id AS f_id",
                "f.bit AS f_bit",
                "cf.clusters_id AS c_id",
            ])
            ->from(
                "pivot_cluster_format AS cf ".
                "INNER JOIN game_formats f ".
                "ON cf.formats_id = f.id"
            )
            ->orderBy([
                "f.id ASC",
                "cf.clusters_id ASC",
            ]);

        $rawFormats = database()
            ->select($rawFormatsStatement)
            ->get();

        $formats = [];
        foreach ($rawFormats as $row) {
            $formatId = &$row["f_id"];
            $formatBit = &$row["f_bit"];
            $clusterId = &$row["c_id"];
            if (!isset($formats[$formatId])) {
                $formats[$formatId] = [
                    "bit" => $formatBit,
                    "clusters" => [],
                ];
            }
            $formats[$formatId]["clusters"][] = $clusterId;
        }

        $sql .= "UPDATE cards SET legality_bit = 0;\n\n";

        // Update legality format by format (no banned cards in that format)
        foreach ($formats as $formatId => $format) {
            $formatBit = $format["bit"];
            $clusters = implode(",", $format["clusters"]);
            $sql .= (
                "UPDATE cards c\n".
                // Exclude banned cards?
                // "LEFT JOIN (\n".
                // "    SELECT DISTINCT(cards_id)\n".
                // "    FROM play_restrictions\n".
                // "    WHERE formats_id = {$formatId} AND deck = 0 AND copies = 0\n".
                // ") AS r ON c.id = r.cards_id\n".
                "SET c.legality_bit = c.legality_bit | (1 << {$formatBit})\n".
                // "WHERE r.cards_id IS NULL AND c.clusters_id IN({$clusters});\n\n\n"
                "WHERE c.clusters_id IN({$clusters});\n\n\n"
            );
        }

        // Update all prints of any card having 1+ reprints to the legality of their last print
        $sql .= (
            "UPDATE cards AS base\n".
            "INNER JOIN (\n".
            "    SELECT name, max(legality_bit) AS legality_bit\n".
            "    FROM cards\n".
            "    WHERE narp = 2\n".
            "    GROUP BY name\n".
            ") AS `last`\n".
            "ON base.name = `last`.name\n".
            "SET base.legality_bit = `last`.legality_bit;\n\n"
        );

        // Update legality on Memoria cards whose base card was not reprinted
        $sql .= (
            "UPDATE cards AS memoriae\n".
            "INNER JOIN (\n".
            "    SELECT name, max(legality_bit) as legality_bit\n".
            "    FROM cards\n".
            "    WHERE name IN(SELECT DISTINCT name FROM cards WHERE narp = 4) AND narp <> 4\n".
            "    GROUP BY name\n".
            ") AS last_print_of_memorias_base_card\n".
            "ON memoriae.name = last_print_of_memorias_base_card.name\n".
            "SET memoriae.legality_bit = last_print_of_memorias_base_card.legality_bit;\n\n"
        );

        // Store .sql file
        $path = path_src("database/set-cards-legality.sql");
        FileSystem::saveFile($path, $sql);

        // Execute the SQL statement
        database()->rawStatement($sql);

        $this->setMessage(
            "Table field \"cards.legality_bit\" was rebuilt. ".
            "The .sql file was stored in\n{$path}"
        );

        return $this;
    }
}
