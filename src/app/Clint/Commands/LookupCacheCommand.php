<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Lookup\Lookup;

class LookupCacheCommand extends Command
{
    public $name = 'lookup:cache';

    public function run(array $options, array $arguments): void
    {
        (Lookup::getInstance())->generateAll()->store();

        $this->message = "Lookup data successfully cached.";
    }
}
