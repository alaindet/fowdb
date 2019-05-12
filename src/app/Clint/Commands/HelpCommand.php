<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Clint\Exceptions\DescriptionNotFoundException as DescriptionNotFound;
use App\Clint\Exceptions\MissingArgumentException as MissingArgument;

class HelpCommand extends Command
{
    public $name = 'help';

    public function run(array $options, array $arguments): void
    {
        // ERROR: Missing command name
        if (!isset($arguments[0])) throw new MissingArgument;

        $name = $arguments[0];
        $kebabName = str_replace(':', '-', $name);
        $filename = fd_path_src("app/Clint/descriptions/{$kebabName}.md");

        // ERROR: Missing description file
        if (!file_exists($filename)) throw new DescriptionNotFound($name);

        // Load the description file
        $description = file_get_contents($filename);

        $this->title = "FoWDB Clint Command: {$name}";
        $this->message = $description;
    }
}
