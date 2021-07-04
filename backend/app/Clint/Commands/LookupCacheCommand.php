<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Lookup\Lookup;

class LookupCacheCommand extends Command
{
    public $name = "lookup:cache";

    public function run(): Command
    {
        (Lookup::getInstance())->build()->store();
        $this->setMessage("Lookup data successfully cached");
        return $this;
    }
}
