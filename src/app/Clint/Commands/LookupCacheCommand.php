<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;

class LookupCacheCommand extends Command
{
    public $name = 'lookup:cache';

    public function run(array $options, array $arguments): void
    {
        $lookup = \App\Services\Lookup\Lookup::getInstance();
        $lookup->generateAll()->cache();

        $this->message = 'Lookup data successfully cached';
    }
}
