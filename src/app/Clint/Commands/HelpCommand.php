<?php

namespace App\Clint\Commands;

use App\Clint\Commands\Command;
use App\Clint\Exceptions\DescriptionNotFoundException;
use App\Clint\Exceptions\MissingArgumentException;
use App\Exceptions\FileSystemException;
use App\Services\FileSystem\FileSystem;

class HelpCommand extends Command
{
    public $name = "help";

    public function run(): Command
    {
        try {
            if (!isset($this->values[0])) {
                throw new MissingArgumentException;
            }
            $commandName = $this->values[0];
            $kebabName = str_replace(":", "-", $commandName);
            $descPath = $this->getPath("descriptions") . "/{$kebabName}.md";
            $this->setTitle("Help - Clint command {$commandName}");
            $this->setMessage(FileSystem::readFile($descPath));
            return $this;
        }

        // ERROR: Missing command name
        catch (MissingArgumentException $exception) {
            throw new MissingArgumentException;
        }
        
        // ERROR: Missing description file
        catch (FileSystemException $exception) {
            throw new DescriptionNotFoundException($commandName);
        }
    }
}
