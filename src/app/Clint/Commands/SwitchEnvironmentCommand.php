<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Clint\Exceptions\MissingArgumentException;
use App\Clint\Exceptions\InvalidArgumentException;
use App\Services\FileSystem\FileSystem;
use App\Services\Configuration\Configuration;

class SwitchEnvironmentCommand extends Command
{
    public $name = 'env:switch';
    private $targets = [
        'development' => 'development',
        'dev'         => 'development',
        'production'  => 'production',
        'prod'        => 'production',
    ];

    public function run(array $options, array $arguments): void
    {
        // ERROR: Missing argument
        if (!isset($arguments[0])) {
            throw new MissingArgumentException;
        }

        // ERROR: Invalid argument
        if (!in_array($arguments[0], array_keys($this->targets))) {
            throw new InvalidArgumentException;
        }

        $target = $this->targets[$arguments[0]];
        $currentEnv = fd_path_src('.env');
        $targetEnv = fd_path_src(".env.{$target}");

        FileSystem::deleteFile($currentEnv);
        FileSystem::copyFile($targetEnv, $currentEnv);

        // Force to cache the new environment
        $config = Configuration::getInstance();
        $config->rebuild();

        $this->message = "Switched environment to {$target}";
    }
}
