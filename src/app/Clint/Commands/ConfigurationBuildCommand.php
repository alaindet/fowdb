<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Configuration\Configuration;

class ConfigurationBuildCommand extends Command
{
    public $name = 'config:rebuild';

    public function run(array $options, array $arguments): void
    {
        $config = Configuration::getInstance();
        $config->build();

        $this->message = "Configuration cache file built.";
    }
}
