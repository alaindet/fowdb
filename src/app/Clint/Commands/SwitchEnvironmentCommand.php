<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Clint\Exceptions\MissingArgumentException;
use App\Clint\Exceptions\InvalidArgumentException;
use App\Services\FileSystem\FileSystem;
use App\Services\Config;

class SwitchEnvironmentCommand extends Command
{
    public $name = 'env:switch';
    private $targets = ['production', 'development'];

    public function run(array $options, array $arguments): void
    {
        // ERROR: Missing argument
        if (!isset($arguments[0])) {
            throw new MissingArgumentException;
        }

        // ERROR: Invalid argument
        if (!in_array($arguments[0], $this->targets)) {
            throw new InvalidArgumentException;
        }

        $target = $arguments[0];
        $currentEnv = path_src('.env');
        $targetEnv = path_src(".env.{$target}");

        FileSystem::deleteFile($currentEnv);
        FileSystem::copyFile($targetEnv, $currentEnv);

        // Force to cache the new environment
        (Config::getInstance())->cache();

        $this->message = "Switched environment to {$target}";
    }
}
