<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;

class ListCommand extends Command
{
    public $name = 'list';

    public function run(array $options, array $arguments): void
    {
        $filename = fd_path_src('app/Clint/descriptions/_all.md');
        $description = file_get_contents($filename);

        $this->title = 'FoWDB Clint CLI tool';
        $this->message = "{$description}\n";
    }
}
