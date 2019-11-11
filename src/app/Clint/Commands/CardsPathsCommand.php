<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\FileSystem\FileSystem;
use App\Utils\Time;

class CardsPathsCommand extends Command
{
    public $name = "cards:paths";

    /**
     * Run the Clint command cards:paths
     *
     * Ex.:
     * $ php clint cards:paths
     *
     * @return Command
     */
    public function run(): Command
    {
        $ts = Time::timestamp();
        $sql = "/* {$ts} */\n\n";

        $backSidesRaw = database()
            ->select(
                statement("select")
                    ->from("card_back_sides")
                    ->orderBy("id ASC")
            )
            ->get();

        $suffixes = "";
        $tabs = str_repeat(" ", 8);
        foreach ($backSidesRaw as $row) {
            $suffixes .= (
                $tabs.
                "WHEN c.back_side = {$row['id']} THEN \"{$row['code']}\"\n"
            );
        }

        $sql .= (
            "UPDATE cards AS c\n".
            "INNER JOIN game_sets AS s ON c.sets_id = s.id\n".
            "SET\n".
            "c.image_path = CONCAT(\n".
            "    'images/cards',\n". // <!-- Change here!
            "    '/', c.clusters_id,\n".
            "    '/', s.code,\n".
            "    '/', LPAD(c.num, 3, '0'),\n".
            "    CASE\n".
                     $suffixes.
            "        ELSE ''\n".
            "    END,\n".
            "    '.jpg'\n".
            "),\n".
            "c.thumb_path = CONCAT(\n".
            "    'images/thumbs',\n". // <!-- Change here!
            "    '/', c.clusters_id,\n".
            "    '/', s.code,\n".
            "    '/', LPAD(c.num, 3, '0'),\n".
            "    CASE\n".
                     $suffixes.
            "        ELSE ''\n".
            "    END,\n".
            "    '.jpg'\n".
            ");"
        );

        // Store .sql file
        $path = path_src("database/set-cards-paths.sql");
        FileSystem::saveFile($path, $sql);

        // Execute the SQL statement
        database()->rawStatement($sql);

        $this->setMessage(
            "Table fields \"cards.image_path\" and \"cards.thumb_path\" were rebuilt. ".
            "The .sql file was stored in\n{$path}"
        );

        return $this;
    }
}
