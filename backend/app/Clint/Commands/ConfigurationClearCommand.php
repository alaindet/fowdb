<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Services\FileSystem\FileSystem;
use App\Exceptions\FileSystemException;
use App\Services\Config\Config;

class ConfigurationClearCommand extends Command
{
    public $name = "config:clear";

    public function run(): Command
    {
        try {
            $path = (Config::getInstance())->getPath();
            FileSystem::deleteFile($path);
            $this->setMessage("Cached config file deleted");
        } catch (FileSystemException $exception) {
            $this->setMessage("No cached config file was found to delete");
        } finally {
            return $this;
        }
    }
}
