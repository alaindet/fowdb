<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Clint\Exceptions\MissingArgumentException;
use App\Clint\Exceptions\InvalidArgumentException;
use App\Services\FileSystem\FileSystem;
use App\Services\Config\Config;

class SwitchEnvironmentCommand extends Command
{
    public $name = "env:switch";
    private $targets = ["production", "development"];

    public function run(): Command
    {
        // ERROR: Missing argument
        if (!isset($this->values[0])) {
            throw new MissingArgumentException;
        }

        // ERROR: Invalid argument
        if (!in_array($this->values[0], $this->targets)) {
            throw new InvalidArgumentException;
        }

        $target = $this->values[0];
        $currentEnv = path_src(".env");
        $targetEnv = path_src(".env.{$target}");

        FileSystem::deleteFile($currentEnv);
        FileSystem::copyFile($targetEnv, $currentEnv);

        // Force to rebuild the configuration
        (Config::getInstance())->build()->store();

        $this->setMessage("Switched environment to {$target}");

        return $this;
    }
}
