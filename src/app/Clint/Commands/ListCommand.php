<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Utils\Paths;

class ListCommand extends Command
{
    public $name = "list";

    public function run(array $options, array $arguments): void
    {
        $filename = Paths::inSrcDir("app/Clint/descriptions/_all.md");
        $description = file_get_contents($filename);

        $this->title = "FoWDB Clint CLI tool";
        $this->message = "{$description}\n";
    }
}
