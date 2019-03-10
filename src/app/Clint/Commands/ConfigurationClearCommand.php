<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\Configuration\Configuration;

class ConfigurationClearCommand extends Command
{
    public $name = 'config:clear';

    public function run(array $options, array $arguments): void
    {
        $config = Configuration::getInstance();
        $config->clear();

        $this->message = "Configuration cached files were deleted.";
    }
}
