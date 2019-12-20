<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Clint\Exceptions\MissingDescriptionsException;
use App\Exceptions\FileSystemException;
use App\Services\FileSystem\FileSystem;

class ListCommand extends Command
{
    public $name = "list";

    public function run(): Command
    {
        try {
            $descsPath = $this->getPath("descriptions") . "/_all.md";
            $descs = FileSystem::readFile($descsPath);
            $this->setTitle("Clint commands");
            $this->setMessage($descs);
        }
        
        // Missing descriptions file
        catch (FileSystemException $exception) {
            throw new MissingDescriptionsException();
        }

        finally {
            return $this;
        }
    }
}
