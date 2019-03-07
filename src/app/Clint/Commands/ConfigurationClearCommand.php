<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\FileSystem\FileSystem;
use App\Services\FileSystem\Exception\FileSystemException;

class ConfigurationClearCommand extends Command
{
    public $name = 'config:clear';

    public function run(array $options, array $arguments): void
    {
        try {
            
            $path = path_cache('config/env.php');
            FileSystem::deleteFile($path);
            $this->message = 'Cached config file cleared';

        } catch (FileSystemException $exception) {

            $this->message = 'Cached config file cleared. No file was found';
            
        }
    }
}
