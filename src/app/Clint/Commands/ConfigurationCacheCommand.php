<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Config\Config;

class ConfigurationCacheCommand extends Command
{
    public $name = "config:cache";

    public function run(): Command
    {
        (Config::getInstance())->build()->store();
        $this->setMessage("Cache config file generated");
        return $this;
    }
}
