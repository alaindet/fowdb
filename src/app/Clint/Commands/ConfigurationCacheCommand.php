<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Config;

class ConfigurationCacheCommand extends Command
{
    public $name = 'config:cache';

    public function run(array $options, array $arguments): void
    {
        $config = Config::getInstance();
        $config->cache(); // Force to cache .env file again

        $this->message = 'Cache config file generated';
    }
}
